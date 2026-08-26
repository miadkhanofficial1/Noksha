<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     */
    public function index(Request $request): View
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with(['resource.category', 'resource.owner'])
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->resource && $item->resource->is_paid ? $item->resource->price : 0.00;
        });

        $total = $subtotal;

        return view('cart.index', compact('cartItems', 'subtotal', 'total'));
    }

    /**
     * Add a resource to the user's shopping cart.
     */
    public function store(Request $request, Resource $resource): RedirectResponse
    {
        Cart::firstOrCreate([
            'user_id' => auth()->id(),
            'resource_id' => $resource->id,
        ]);

        return redirect()->back()->with('success', "🛒 Added \"{$resource->title}\" to your cart!");
    }

    /**
     * Remove a resource from the user's shopping cart.
     */
    public function destroy(Resource $resource): RedirectResponse
    {
        Cart::where('user_id', auth()->id())
            ->where('resource_id', $resource->id)
            ->delete();

        return redirect()->back()->with('success', "Item removed from your cart.");
    }
}
