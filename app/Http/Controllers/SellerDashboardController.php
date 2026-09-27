<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\RedirectResponse;

class SellerDashboardController extends Controller
{
    /**
     * Redirect to the Unified Dashboard in Seller Studio mode.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard', ['mode' => 'seller']);
    }

    /**
     * Delete a resource owned by the authenticated seller.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        if ($resource->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $resource->delete();

        return redirect()->to(route('dashboard') . '#designs')
            ->with('success', 'Design deleted successfully.');
    }
}
