<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display the user's saved wishlist.
     */
    public function index(Request $request): View
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with(['resource.category', 'resource.owner'])
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Save a resource to the user's wishlist.
     */
    public function store(Request $request, Resource $resource): RedirectResponse
    {
        Wishlist::firstOrCreate([
            'user_id' => auth()->id(),
            'resource_id' => $resource->id,
        ]);

        return redirect()->back()->with('success', "❤️ Added \"{$resource->title}\" to your saved wishlist!");
    }

    /**
     * Remove a resource from the user's wishlist.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        Wishlist::where('user_id', auth()->id())
            ->where('resource_id', $resource->id)
            ->delete();

        return redirect()->back()->with('success', "Removed item from your wishlist.");
    }
}
