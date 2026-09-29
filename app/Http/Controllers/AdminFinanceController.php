<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AdminFinanceController extends Controller
{
    /**
     * Authorize that the current authenticated user is an administrator.
     */
    protected function checkAdminAuthorization(): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized. Please sign in to access the financial control room.');
        }

        $isAdmin = false;
        if (method_exists($user, 'isAdmin')) {
            $isAdmin = $user->isAdmin();
        } elseif (isset($user->is_admin) && $user->is_admin) {
            $isAdmin = true;
        } elseif (in_array($user->role ?? '', ['admin', 'super_admin'])) {
            $isAdmin = true;
        }

        abort_unless($isAdmin, 403, 'Forbidden. You do not have permission to access the financial analytics console.');
    }

    /**
     * Display the Super Admin Financial Analytics & Platform Commission overview dashboard.
     */
    public function index(Request $request): View
    {
        $this->checkAdminAuthorization();

        // 1. Template Sales Gross Volume
        $orderSales = (float) Order::where('payment_status', 'completed')->sum('total');
        $walletSales = (float) WalletTransaction::where('type', 'template_purchase')->sum('amount');
        $templateGmv = max($orderSales, $walletSales);

        // 2. Contest Prize Pools & Fees
        $contestPrizeGmv = (float) Contest::whereNotIn('status', ['rejected', 'cancelled'])->sum('prize_amount');
        $contestFees = (float) Contest::whereNotIn('status', ['rejected', 'cancelled'])->sum('posting_fee');

        // Platform Gross Volume (GMV): Total BDT transacted across template sales & contest prize pools
        $totalGmv = $templateGmv + $contestPrizeGmv;

        // Net Platform Commission Revenue: 15% marketplace commission from template sales + contest escrow/posting fees
        $templateCommission = $templateGmv * 0.15;
        $totalCommissionRevenue = $templateCommission + $contestFees;

        // 3. Total AI Credit Sales: Sum of BDT revenue from users purchasing AI token packages
        $totalAiCreditSales = (float) WalletTransaction::where('type', 'credit_purchase')->sum('amount');

        // 4. Active Escrow Locked Funds: Ongoing, unawarded contests
        $activeEscrowFunds = (float) Contest::whereIn('status', ['active', 'judging', 'handover'])->sum('prize_amount');

        // 5. Pending Payouts Pipeline: Total BDT of pending creator withdrawal requests
        $pendingPayoutsSum = (float) Withdrawal::where('status', 'pending')->sum('amount');
        $pendingPayoutsCount = Withdrawal::where('status', 'pending')->count();
        $approvedPayoutsSum = (float) Withdrawal::where('status', 'approved')->sum('amount');

        // Pending Creator Withdrawals Queue
        $pendingWithdrawals = Withdrawal::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Recent Completed/Processed Withdrawals
        $recentWithdrawals = Withdrawal::with('user')
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->take(8)
            ->get();

        // 6. Ledger Audit Log (Paginated & Searchable)
        $search = trim($request->input('search', ''));
        $typeFilter = $request->input('type', 'all');

        $ledgerQuery = WalletTransaction::with(['user', 'wallet'])->latest();

        if (!empty($search)) {
            $ledgerQuery->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('reference_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($typeFilter !== 'all' && !empty($typeFilter)) {
            $ledgerQuery->where('type', $typeFilter);
        }

        $ledgerTransactions = $ledgerQuery->paginate(15)->withQueryString();

        // Available distinct transaction types for filter
        $transactionTypes = WalletTransaction::select('type')
            ->distinct()
            ->pluck('type')
            ->filter()
            ->values();

        return view('admin.finance.index', compact(
            'totalGmv',
            'templateGmv',
            'contestPrizeGmv',
            'totalCommissionRevenue',
            'templateCommission',
            'contestFees',
            'totalAiCreditSales',
            'activeEscrowFunds',
            'pendingPayoutsSum',
            'pendingPayoutsCount',
            'approvedPayoutsSum',
            'pendingWithdrawals',
            'recentWithdrawals',
            'ledgerTransactions',
            'transactionTypes',
            'search',
            'typeFilter'
        ));
    }

    /**
     * Approve a pending creator payout request and mark transaction as completed.
     */
    public function approveWithdrawal(int|string $id): RedirectResponse
    {
        $this->checkAdminAuthorization();

        $withdrawal = Withdrawal::with('user')->findOrFail($id);

        if ($withdrawal->status === 'approved') {
            return back()->with('info', "Withdrawal #WTH-{$withdrawal->id} has already been approved.");
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update(['status' => 'approved']);

            // Find matching pending withdrawal transaction in wallet ledger and mark as completed
            $tx = WalletTransaction::where('user_id', $withdrawal->user_id)
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->where('amount', $withdrawal->amount)
                ->latest()
                ->first();

            if ($tx) {
                $tx->update(['status' => 'completed']);
            }

            // Send notification to the creator
            Notification::send(
                $withdrawal->user_id,
                'Payout Approved & Disbursed',
                "Your payout request #WTH-{$withdrawal->id} for ৳" . number_format($withdrawal->amount, 2) . " via " . strtoupper($withdrawal->payment_method) . " ({$withdrawal->account_details}) has been approved and paid out.",
                'finance',
                route('seller.payouts.index')
            );
        });

        return back()->with('success', "✅ Payout #WTH-{$withdrawal->id} of ৳" . number_format($withdrawal->amount, 2) . " for {$withdrawal->user->name} was approved successfully.");
    }

    /**
     * Reject a creator payout request, log reason, and automatically refund funds to their wallet.
     */
    public function rejectWithdrawal(Request $request, int|string $id): RedirectResponse
    {
        $this->checkAdminAuthorization();

        $withdrawal = Withdrawal::with('user')->findOrFail($id);

        if ($withdrawal->status === 'rejected') {
            return back()->with('info', "Withdrawal #WTH-{$withdrawal->id} has already been rejected.");
        }

        $reason = trim($request->input('reason', $request->input('note', '')));
        if (empty($reason)) {
            $reason = 'Payout request declined by administrator (Verification or account detail mismatch).';
        }

        DB::transaction(function () use ($withdrawal, $reason) {
            $withdrawal->update([
                'status' => 'rejected',
                'note' => $reason,
            ]);

            // Refund balance to creator's wallet
            $seller = $withdrawal->user;
            $wallet = $seller->wallet ?? Wallet::firstOrCreate(['user_id' => $seller->id], ['balance' => 0.00]);
            $wallet->increment('balance', $withdrawal->amount);

            if (Schema::hasColumn('users', 'balance')) {
                $seller->update(['balance' => $wallet->balance]);
            }

            // Mark original pending transaction as rejected
            $origTx = WalletTransaction::where('user_id', $withdrawal->user_id)
                ->where('type', 'withdrawal')
                ->where('status', 'pending')
                ->where('amount', $withdrawal->amount)
                ->latest()
                ->first();

            if ($origTx) {
                $origTx->update(['status' => 'rejected']);
            }

            // Create refund transaction in wallet ledger
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $seller->id,
                'type' => 'refund',
                'amount' => $withdrawal->amount,
                'credits_transacted' => 0,
                'balance_after' => $wallet->balance,
                'description' => "Refund for rejected payout #WTH-{$withdrawal->id}: {$reason}",
                'status' => 'completed',
            ]);

            // Notify creator
            Notification::send(
                $withdrawal->user_id,
                'Withdrawal Request Declined',
                "Your payout request #WTH-{$withdrawal->id} for ৳" . number_format($withdrawal->amount, 2) . " was declined ({$reason}). The full amount of ৳" . number_format($withdrawal->amount, 2) . " has been refunded back to your wallet.",
                'finance',
                route('seller.payouts.index')
            );
        });

        return back()->with('success', "⚠️ Payout #WTH-{$withdrawal->id} rejected. ৳" . number_format($withdrawal->amount, 2) . " was refunded to {$withdrawal->user->name}'s wallet.");
    }
}
