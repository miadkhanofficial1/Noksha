<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PayoutController extends Controller
{
    /**
     * Minimum allowed payout threshold in BDT.
     */
    public const MIN_WITHDRAWAL_AMOUNT = 500.00;

    /**
     * Display the Seller Earnings & Withdrawal management dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        // 1. Calculate Gross Sales from completed orders containing this user's resources
        $orderSales = (float) OrderItem::whereHas('resource', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->whereHas('order', function ($query) {
            $query->where('payment_status', 'completed');
        })->sum('price');

        // Check wallet template sales transactions
        $walletSales = (float) WalletTransaction::where('user_id', $user->id)
            ->where('type', 'template_sale')
            ->sum('amount');
        $calculatedGross = $walletSales > 0 ? round($walletSales / 0.85, 2) : 0.00;

        $totalGrossSales = max($orderSales, $calculatedGross);

        // 2. Net Available Balance from linked Wallet
        $availableBalance = (float) ($wallet->balance ?? 0.00);

        // 3. Pending Withdrawal Sum
        $pendingWithdrawals = (float) Withdrawal::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        // 4. Lifetime Completed Payouts
        $completedPayouts = (float) Withdrawal::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('amount');

        // 5. Paginated Withdrawal Requests Ledger
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $minThreshold = self::MIN_WITHDRAWAL_AMOUNT;

        return view('seller.payouts', compact(
            'totalGrossSales',
            'availableBalance',
            'pendingWithdrawals',
            'completedPayouts',
            'withdrawals',
            'minThreshold'
        ));
    }

    /**
     * Submit a new withdrawal payout request.
     */
    public function requestWithdrawal(Request $request): RedirectResponse
    {
        $minThreshold = self::MIN_WITHDRAWAL_AMOUNT;

        $validated = $request->validate([
            'amount' => ['required', 'numeric', "min:{$minThreshold}"],
            'payment_method' => ['required', 'string', 'in:bkash,nagad,bank'],
            'account_details' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:500'],
        ], [
            'amount.required' => 'Please enter the withdrawal amount.',
            'amount.min' => "Minimum payout threshold is ৳" . number_format($minThreshold, 2) . ".",
            'payment_method.required' => 'Please select a payout method.',
            'payment_method.in' => 'Selected payout method is not supported.',
            'account_details.required' => 'Please enter your phone number or bank account details.',
        ]);

        $user = auth()->user();
        $wallet = $user->wallet;
        $amount = (float) $validated['amount'];

        // Ensure sufficient available balance
        if ($wallet->balance < $amount) {
            return back()->with('error', "Insufficient wallet balance. You requested ৳" . number_format($amount, 2) . ", but your available balance is ৳" . number_format($wallet->balance, 2) . ".");
        }

        // Deduct from wallet balance
        $wallet->decrement('balance', $amount);
        if (Schema::hasColumn('users', 'balance')) {
            $user->update(['balance' => $wallet->balance]);
        }

        // Create Withdrawal record
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'payment_method' => $validated['payment_method'],
            'account_details' => $validated['account_details'],
            'status' => 'pending',
            'note' => $validated['note'] ?? null,
        ]);

        $methodLabel = match ($validated['payment_method']) {
            'bkash' => 'bKash Personal',
            'nagad' => 'Nagad',
            'bank' => 'Bank Transfer',
            default => strtoupper($validated['payment_method']),
        };

        // Log transaction in wallet_transactions
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'credits_transacted' => 0,
            'balance_after' => $wallet->balance,
            'description' => "Payout request #WTH-{$withdrawal->id} via {$methodLabel} ({$validated['account_details']})",
            'status' => 'pending',
        ]);

        // Send in-app notification to the seller
        Notification::send(
            $user->id,
            'Withdrawal Request Submitted',
            "Your payout request #WTH-{$withdrawal->id} for ৳" . number_format($amount, 2) . " via {$methodLabel} is pending approval and processing.",
            'seller',
            route('seller.payouts.index')
        );

        return back()->with('success', "✅ Withdrawal request for ৳" . number_format($amount, 2) . " submitted successfully! Our financial team will review and disburse your funds within 24-48 business hours.");
    }
}
