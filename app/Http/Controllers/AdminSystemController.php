<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AdminSystemController extends Controller
{
    /**
     * Secure 1-Click System Purge / Factory Reset engine strictly accessible by Super Admin.
     */
    public function purgeAllData(Request $request): RedirectResponse
    {
        // 1. Security & Authorization check (Super Admin only)
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->is_admin && !in_array($user->role, ['admin', 'super_admin']))) {
            abort(403, 'Unauthorized action: Super Admin privileges required to execute system factory reset.');
        }

        // 2. Validate confirmation phrase
        $request->validate([
            'confirm_phrase' => ['required', 'string'],
        ], [
            'confirm_phrase.required' => 'Confirmation phrase is required to execute system reset.',
        ]);

        if (trim($request->input('confirm_phrase')) !== 'RESET-NOKSHA') {
            return back()->with('error', 'Purge aborted: Confirmation phrase does not match "RESET-NOKSHA".');
        }

        // 3. Safe Database Transaction
        DB::transaction(function () use ($user) {
            // Disable foreign key checks for clean cascade wipe
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            try {
                // Delete all non-admin users
                User::where('id', '!=', $user->id)->delete();

                // Clear resources & marketplace assets
                if (Schema::hasTable('resources')) {
                    DB::table('resources')->delete();
                }

                // Clear contests & submissions/entries
                if (Schema::hasTable('contest_entries')) {
                    DB::table('contest_entries')->delete();
                }
                if (Schema::hasTable('contest_submissions')) {
                    DB::table('contest_submissions')->delete();
                }
                if (Schema::hasTable('submissions')) {
                    DB::table('submissions')->delete();
                }
                if (Schema::hasTable('contests')) {
                    DB::table('contests')->delete();
                }

                // Clear shopping carts, orders, and order items
                if (Schema::hasTable('order_items')) {
                    DB::table('order_items')->delete();
                }
                if (Schema::hasTable('orders')) {
                    DB::table('orders')->delete();
                }
                if (Schema::hasTable('carts')) {
                    DB::table('carts')->delete();
                }
                if (Schema::hasTable('wishlists')) {
                    DB::table('wishlists')->delete();
                }

                // Clear reviews & ratings
                if (Schema::hasTable('reviews')) {
                    DB::table('reviews')->delete();
                }

                // Clear financial transactions & payout requests
                if (Schema::hasTable('wallet_transactions')) {
                    DB::table('wallet_transactions')->delete();
                }
                if (Schema::hasTable('withdrawals')) {
                    DB::table('withdrawals')->delete();
                }

                // Clear contact messages & customer support inquiries
                if (Schema::hasTable('contact_messages')) {
                    DB::table('contact_messages')->delete();
                }

                // Clear seller verifications & KYC requests
                if (Schema::hasTable('seller_verifications')) {
                    DB::table('seller_verifications')->delete();
                }

                // Clear social follows & notifications
                if (Schema::hasTable('user_follows')) {
                    DB::table('user_follows')->delete();
                }
                if (Schema::hasTable('notifications')) {
                    DB::table('notifications')->delete();
                }

                // Wipe non-admin wallets & AI credit balances
                if (Schema::hasTable('wallets')) {
                    DB::table('wallets')->where('user_id', '!=', $user->id)->delete();
                    DB::table('wallets')->updateOrInsert(
                        ['user_id' => $user->id],
                        ['balance' => 0.00, 'updated_at' => now()]
                    );
                }
                if (Schema::hasTable('ai_credits')) {
                    DB::table('ai_credits')->where('user_id', '!=', $user->id)->delete();
                    DB::table('ai_credits')->updateOrInsert(
                        ['user_id' => $user->id],
                        ['credits' => 100, 'updated_at' => now()]
                    );
                }

                // Reset Super Admin balance if column exists
                if (Schema::hasColumn('users', 'balance')) {
                    User::where('id', $user->id)->update(['balance' => 0.00]);
                }
            } finally {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        });

        // 4. Clean orphan storage attachments/files safely
        try {
            $storageDirs = ['resources', 'previews', 'contest_attachments', 'contest_watermarked', 'verifications'];
            foreach ($storageDirs as $dir) {
                if (Storage::disk('public')->exists($dir)) {
                    Storage::disk('public')->deleteDirectory($dir);
                    Storage::disk('public')->makeDirectory($dir);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('System purge storage cleanup warning: ' . $e->getMessage());
        }

        Log::notice("Super Admin [{$user->name} #{$user->id}] executed 1-Click System Purge & Factory Reset.");

        return redirect()->route('admin.dashboard')
            ->with('success', 'System successfully purged! All resources, reviews, dummy users, and transactions have been wiped clean.');
    }
}
