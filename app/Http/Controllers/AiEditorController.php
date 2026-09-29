<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiEditorController extends Controller
{
    /**
     * Display the 2-Column AI Template Customizer workspace interface.
     */
    public function edit(string|int $id): View
    {
        $resource = Resource::with(['owner', 'category'])->find($id);

        if (!$resource) {
            $resource = Resource::where('slug', $id)->with(['owner', 'category'])->first();
        }

        if (!$resource) {
            // Fallback to the latest approved resource if available for a smoother demo experience
            $resource = Resource::with(['owner', 'category'])->latest()->first();
        }

        if (!$resource) {
            abort(404, 'Template not found for AI customization.');
        }

        $isAdmin = auth()->user()?->isAdmin() ?? false;
        $userCredits = $isAdmin ? 'unlimited' : (auth()->user()->aiCredit?->credits ?? auth()->user()->credits ?? 0);

        return view('editor.workspace', compact('resource', 'userCredits', 'isAdmin'));
    }

    /**
     * Deduct 1 AI generation credit from the user's account before synthesis.
     */
    public function deductCredit(Request $request): JsonResponse
    {
        $user = auth()->user();
        $templateId = $request->input('template_id', 'custom');

        // Admin / Super Admin Full Bypass: Do not deduct credits or record debit
        if ($user && $user->isAdmin()) {
            return response()->json([
                'success' => true,
                'remaining_credits' => 'unlimited',
                'is_admin' => true,
                'message' => 'Admin bypass active: unlimited generation privileges.',
            ]);
        }

        $aiCredit = $user->aiCredit;
        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = \App\Models\Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0.00]
            );
        }

        // Check if user has sufficient credits
        if (!$aiCredit || $aiCredit->credits < 1) {
            return response()->json([
                'success' => false,
                'error' => 'insufficient_credits',
                'remaining_credits' => $aiCredit?->credits ?? 0,
                'is_admin' => false,
                'message' => 'You do not have enough AI credits to generate this variation.',
            ], 402);
        }

        // Deduct 1 credit
        $aiCredit->decrement('credits', 1);
        $remainingCredits = $aiCredit->fresh()->credits;

        // Record transaction in wallet_transactions
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'type' => 'ai_generation_fee',
            'amount' => 0.00,
            'credits_transacted' => -1,
            'balance_after' => $wallet->balance,
            'description' => 'AI Customizer generation for Template #' . $templateId,
            'status' => 'completed',
        ]);

        return response()->json([
            'success' => true,
            'remaining_credits' => $remainingCredits,
            'is_admin' => false,
            'message' => '1 AI generation credit deducted successfully.',
        ]);
    }

    /**
     * Handle AI design generation request.
     */
    public function generate(Request $request, string|int $id): JsonResponse
    {
        $resource = Resource::find($id);
        if (!$resource) {
            $resource = Resource::where('slug', $id)->first();
        }

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.',
            ], 404);
        }

        $validated = $request->validate([
            'headline' => ['required', 'string', 'max:150'],
            'subtitle' => ['nullable', 'string', 'max:250'],
            'brand_name' => ['nullable', 'string', 'max:80'],
            'cta_text' => ['nullable', 'string', 'max:50'],
            'color_tone' => ['required', 'string', 'in:modern_dark,vibrant_gradient,minimal_light,corporate_blue'],
        ]);

        $user = auth()->user();
        $isAdmin = $user && $user->isAdmin();

        return response()->json([
            'success' => true,
            'message' => 'AI graphic successfully customized and rendered.',
            'data' => [
                'resource_id' => $resource->id,
                'title' => $resource->title,
                'headline' => $validated['headline'],
                'subtitle' => $validated['subtitle'] ?? '',
                'brand_name' => $validated['brand_name'] ?? 'Brand',
                'cta_text' => $validated['cta_text'] ?? 'Explore Now',
                'color_tone' => $validated['color_tone'],
                'credits_remaining' => $isAdmin ? 'unlimited' : ($user->aiCredit?->credits ?? 0),
                'is_admin' => $isAdmin,
            ],
        ]);
    }
}
