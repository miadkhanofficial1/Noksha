<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Minimum allowed withdrawal payout threshold in BDT.
     */
    public const MIN_WITHDRAWAL_AMOUNT = 500.00;

    /**
     * Display the Central Financial Hub & BDT Wallet Ledger.
     */
    public function index(): View
    {
        $user = auth()->user();
        $wallet = $user->wallet ?? Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00, 'earnings_balance' => 0.00]
        );

        // Paginated transaction ledger
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        // Permissions & balances
        $canWithdraw = $user->isSellerOrCreator() || $user->isAdmin();
        $balance = (float) ($wallet->balance ?? $user->balance ?? 0.00);
        $earningsBalance = (float) ($user->earnings_balance ?? $wallet->earnings_balance ?? 0.00);

        return view('wallet.index', compact('wallet', 'transactions', 'canWithdraw', 'balance', 'earningsBalance'));
    }

    /**
     * Manual deposit confirmation (bKash, Nagad, Bank Card).
     */
    public function deposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10', 'max:100000'],
            'payment_method' => ['required', 'string', 'in:bkash,nagad,rocket,card'],
            'sender_number' => ['nullable', 'string', 'max:50'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
        ], [
            'amount.required' => 'Please enter a deposit amount.',
            'amount.min' => 'Minimum deposit amount is ৳10.00.',
            'payment_method.required' => 'Please select a payment method.',
        ]);

        $user = auth()->user();
        $wallet = $user->wallet ?? Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00, 'earnings_balance' => 0.00]
        );

        $methodName = match ($validated['payment_method']) {
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
            'card' => 'Bank Card',
            default => 'Payment Gateway',
        };

        $amount = (float) $validated['amount'];
        $sender = $validated['sender_number'] ?? null;
        $trxId = $validated['transaction_id'] ?? null;

        $descParts = ["Wallet deposit via {$methodName}"];
        if ($sender) {
            $descParts[] = "Sender: {$sender}";
        }
        if ($trxId) {
            $descParts[] = "TrxID: {$trxId}";
        }
        $description = implode(' | ', $descParts);

        $wallet->deposit(
            $amount,
            $description,
            'deposit',
            0
        );

        $successMsg = "✅ Successfully deposited ৳" . number_format($amount, 2) . " via {$methodName}!";
        if ($trxId) {
            $successMsg .= " (TrxID: {$trxId})";
        }
        $successMsg .= " Available balance is now ৳" . number_format($wallet->fresh()->balance, 2) . ".";

        return back()->with('success', $successMsg);
    }

    /**
     * Submit a creator payout / withdrawal request.
     */
    public function withdraw(Request $request): RedirectResponse
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

        if (!$user->isSellerOrCreator() && !$user->isAdmin()) {
            return back()->with('error', 'Only verified creators and sellers can request payouts from their earnings.');
        }

        $wallet = $user->wallet ?? Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0.00, 'earnings_balance' => 0.00]
        );

        $amount = (float) $validated['amount'];
        $availableEarnings = (float) ($user->earnings_balance ?? 0.00);
        $availableWallet = (float) ($wallet->balance ?? 0.00);

        // Verify sufficient balance
        if ($availableEarnings >= $amount) {
            // Deduct from creator earnings
            if (Schema::hasColumn('users', 'earnings_balance')) {
                $user->decrement('earnings_balance', $amount);
            }
            if (Schema::hasColumn('wallets', 'earnings_balance')) {
                $wallet->decrement('earnings_balance', $amount);
            }
        } elseif ($availableWallet >= $amount) {
            // Deduct from wallet balance
            $wallet->decrement('balance', $amount);
            if (Schema::hasColumn('users', 'balance')) {
                $user->decrement('balance', $amount);
            }
        } else {
            $maxAvail = max($availableEarnings, $availableWallet);
            return back()->with('error', "Insufficient balance for withdrawal. Requested ৳" . number_format($amount, 2) . ", but your available balance is ৳" . number_format($maxAvail, 2) . ". Minimum payout is ৳500.00.");
        }

        // Create Withdrawal model record
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
            'nagad' => 'Nagad Personal',
            'bank' => 'Bank Transfer',
            default => strtoupper($validated['payment_method']),
        };

        // Record pending withdrawal in wallet_transactions
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amount,
            'credits_transacted' => 0,
            'balance_after' => $wallet->fresh()->balance,
            'description' => "Payout request #WTH-{$withdrawal->id} via {$methodLabel} ({$validated['account_details']})",
            'status' => 'pending',
        ]);

        // Send in-app notification if Notification model exists
        if (class_exists(Notification::class)) {
            Notification::send(
                $user->id,
                'Withdrawal Request Submitted',
                "Your payout request #WTH-{$withdrawal->id} for ৳" . number_format($amount, 2) . " via {$methodLabel} is pending approval and processing.",
                'seller',
                route('wallet.index')
            );
        }

        return back()->with('success', "✅ Payout request for ৳" . number_format($amount, 2) . " submitted successfully! Our financial team will review and disburse your funds within 24–48 business hours.");
    }

    /**
     * Legacy credit purchase method (maintained for route stability).
     */
    public function buyCredits(Request $request): RedirectResponse
    {
        return redirect()->route('wallet.index')->with('info', 'Credit bundles have been retired. All Noksha services and templates now use pure BDT wallet balances directly.');
    }
}
