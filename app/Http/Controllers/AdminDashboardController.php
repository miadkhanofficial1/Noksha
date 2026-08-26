<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Resource;
use App\Models\SellerVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display Super Admin Central Marketplace Management Dashboard & Analytics.
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Super Admin Role Protection Guard
        if (!in_array(auth()->user()->role ?? 'user', ['admin', 'super_admin'])) {
            return redirect()->route('home')->with('warning', 'Access restricted. Super Admin privileges required.');
        }
        // 1. Overview Statistics
        $totalUsers = User::count();
        $totalResources = Resource::count();
        $totalOrders = Order::count();
        $revenue = Order::where('payment_status', 'completed')->sum('total');
        $pendingVerifications = SellerVerification::where('status', 'pending')->count();
        $pendingResources = Resource::where('status', 'pending')->count();

        // 2. Resource Management Table
        $resources = Resource::with(['owner', 'category'])
            ->latest()
            ->paginate(10, ['*'], 'resources_page');

        // 3. User Management Table
        $users = User::withCount(['resources', 'orders'])
            ->latest()
            ->paginate(10, ['*'], 'users_page');

        // 4. Recent Activity Feed
        $recentUsers = User::latest()->take(3)->get()->map(fn($u) => [
            'type' => 'user',
            'title' => 'New User Registered',
            'desc' => "{$u->name} (@{$u->username}) joined Noksha.",
            'time' => $u->created_at->diffForHumans(),
            'icon' => 'bi-person-plus-fill text-primary',
        ]);

        $recentUploads = Resource::with('owner')->latest()->take(3)->get()->map(fn($r) => [
            'type' => 'upload',
            'title' => 'Resource Uploaded',
            'desc' => "\"{$r->title}\" uploaded by " . ($r->owner->name ?? 'Seller') . '.',
            'time' => $r->created_at->diffForHumans(),
            'icon' => 'bi-cloud-arrow-up-fill text-info',
        ]);

        $recentOrders = Order::with('user')->latest()->take(3)->get()->map(fn($o) => [
            'type' => 'order',
            'title' => 'Order Completed',
            'desc' => "Order #{$o->order_number} (৳" . number_format($o->total, 2) . ") placed by " . ($o->user->name ?? 'Buyer') . '.',
            'time' => $o->created_at->diffForHumans(),
            'icon' => 'bi-bag-check-fill text-success',
        ]);

        $recentActivity = $recentUsers->concat($recentUploads)->concat($recentOrders)->sortByDesc('time')->take(6);

        // 5. Chart Analytics Demo Data
        $chartData = [
            'monthlyLabels' => ['Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            'monthlyUploads' => [12, 19, 24, 35, 42, Resource::count()],
            'monthlyOrders' => [8, 15, 21, 30, 48, Order::count()],
            'topCategories' => Category::withCount('resources')->take(5)->pluck('name')->toArray() ?: ['Mobile UI', 'Vectors', 'Icons', 'Web Templates', '3D Assets'],
            'categoryCounts' => Category::withCount('resources')->take(5)->pluck('resources_count')->toArray() ?: [42, 35, 28, 20, 15],
            'userGrowth' => [15, 30, 55, 90, 130, User::count()],
        ];

        // Popular Search & Upload Tags Widget Data
        $allMarketplaceTags = Resource::pluck('tags')->flatten()->filter()->toArray();
        $adminTagCounts = array_count_values(array_map('strtolower', $allMarketplaceTags));
        arsort($adminTagCounts);
        $popularSearchTags = array_slice($adminTagCounts, 0, 10, true);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalResources',
            'totalOrders',
            'revenue',
            'pendingVerifications',
            'pendingResources',
            'resources',
            'users',
            'recentActivity',
            'chartData',
            'popularSearchTags'
        ));
    }

    /**
     * Suspend or activate a user account (Admin action).
     */
    public function toggleUserStatus(User $user): RedirectResponse
    {
        if (!in_array(auth()->user()->role ?? 'user', ['admin', 'super_admin'])) {
            return redirect()->route('home')->with('warning', 'Access restricted.');
        }

        $newStatus = $user->status === 'suspended' ? 'active' : 'suspended';
        $user->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "User account \"{$user->name}\" is now " . strtoupper($newStatus) . '.');
    }

    /**
     * Broadcast a system-wide notification to all users.
     */
    public function broadcastNotification(Request $request): RedirectResponse
    {
        if (!in_array(auth()->user()->role ?? 'user', ['admin', 'super_admin'])) {
            return redirect()->route('home')->with('warning', 'Access restricted.');
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $users = User::all();
        foreach ($users as $user) {
            Notification::send(
                $user->id,
                $validated['title'],
                $validated['message'],
                'system',
                route('home')
            );
        }

        return redirect()->back()
            ->with('success', '📣 System-wide notification broadcasted successfully to all users!');
    }
}
