<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Credit packages catalog.
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $packages = [
        'starter' => [
            'key' => 'starter',
            'name' => 'Starter Pack',
            'price' => 100.00,
            'credits' => 25,
            'rate' => '4.00',
            'badge' => null,
            'description' => 'Ideal for testing AI customizer workflows and quick variations.',
        ],
        'creator' => [
            'key' => 'creator',
            'name' => 'Creator Pack',
            'price' => 250.00,
            'credits' => 75,
            'rate' => '3.33',
            'badge' => 'Most Popular',
            'description' => 'Perfect for active designers customizing client graphics regularly.',
        ],
        'pro' => [
            'key' => 'pro',
            'name' => 'Pro Agency Pack',
            'price' => 500.00,
            'credits' => 200,
            'rate' => '2.50',
            'badge' => 'Best Value',
            'description' => 'Highest token volume for studios and power users at minimum per-edit cost.',
        ],
    ];

    /**
     * Display the Billing, Wallet & AI Credit Storefront.
     */
    public function index(): View
    {
        $user = auth()->user();
        $wallet = $user->wallet;
        $aiCredit = $user->aiCredit;

        // Paginated transaction ledger
        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Count of AI generation fees or edits transacted
        $totalGenerations = WalletTransaction::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('type', 'ai_generation_fee')
                    ->orWhere('description', 'like', '%AI%generation%');
            })
            ->count();

        $packages = $this->packages;

        return view('wallet.index', compact('wallet', 'aiCredit', 'transactions', 'totalGenerations', 'packages'));
    }

    /**
     * Purchase AI credits bundle using wallet funds.
     */
    public function buyCredits(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'package' => ['required', 'string', 'in:starter,creator,pro'],
        ], [
            'package.required' => 'Please select a valid credit package.',
            'package.in' => 'The selected package is invalid.',
        ]);

        $pack = $this->packages[$validated['package']];
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('wallet.index')->with('info', 'Admin accounts already have unlimited AI tokens active. No bundle purchase needed!');
        }

        $wallet = $user->wallet;
        $aiCredit = $user->aiCredit;

        if (!$wallet->hasBalance($pack['price'])) {
            $shortfall = $pack['price'] - $wallet->balance;
            return back()->with('error', "Insufficient wallet balance to purchase {$pack['name']}. You need ৳" . number_format($shortfall, 2) . " more. Please recharge your wallet below.");
        }

        // Deduct from wallet
        $wallet->decrement('balance', $pack['price']);
        if (Schema::hasColumn('users', 'balance')) {
            $user->update(['balance' => $wallet->balance]);
        }

        // Add credits
        $aiCredit->addCredits($pack['credits']);

        // Record transaction
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'type' => 'credit_purchase',
            'amount' => $pack['price'],
            'credits_transacted' => $pack['credits'],
            'balance_after' => $wallet->balance,
            'description' => "Purchased {$pack['name']} (+{$pack['credits']} AI Credits)",
            'status' => 'completed',
        ]);

        return back()->with('success', "🎉 Successfully purchased {$pack['name']}! Added {$pack['credits']} AI generation tokens to your account.");
    }

    /**
     * Simulated instant wallet funds recharge (bKash, Nagad, Card).
     */
    public function deposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10', 'max:50000'],
            'payment_method' => ['required', 'string', 'in:bkash,nagad,card'],
        ], [
            'amount.required' => 'Please enter a deposit amount.',
            'amount.min' => 'Minimum top-up amount is ৳10.00.',
            'payment_method.required' => 'Please select a payment method.',
        ]);

        $user = auth()->user();
        $wallet = $user->wallet;

        $methodName = match ($validated['payment_method']) {
            'bkash' => 'bKash Instant',
            'nagad' => 'Nagad Direct',
            'card' => 'Visa / Mastercard',
            default => 'Payment Gateway',
        };

        $amount = (float) $validated['amount'];

        $wallet->deposit(
            $amount,
            "Wallet recharge via {$methodName}",
            'deposit',
            0
        );

        return back()->with('success', "✅ Recharged ৳" . number_format($amount, 2) . " successfully via {$methodName}! Your new balance is ৳" . number_format($wallet->fresh()->balance, 2) . ".");
    }
}
