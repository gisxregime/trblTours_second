<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->dashboardRouteName());
    }

    public function tourist(): View
    {
        return view('dashboards.tourist');
    }

    public function guide(): View
    {
        return view('dashboards.guide');
    }

    public function admin(): View
    {
        $totalUsers = User::query()->count();
        $activeGuides = User::query()
            ->whereIn('role', ['guide', 'tour_guide'])
            ->where('status', 'active')
            ->count();

        $featuredTours = Schema::hasTable('tours') && Schema::hasColumn('tours', 'is_featured')
            ? Tour::query()->where('is_featured', true)->count()
            : 0;

        $pendingGuideApprovals = Schema::hasTable('tour_guides_profile') && Schema::hasColumn('tour_guides_profile', 'approved_by_admin')
            ? DB::table('tour_guides_profile')->where('approved_by_admin', false)->count()
            : 0;

        $recentUsers = User::query()
            ->select(['name', 'email', 'role', 'status', 'created_at'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboards.admin', [
            'stats' => [
                'total_users' => $totalUsers,
                'active_guides' => $activeGuides,
                'pending_guide_approvals' => $pendingGuideApprovals,
                'featured_tours' => $featuredTours,
            ],
            'recentUsers' => $recentUsers,
        ]);
    }
}
