<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'total_users' => User::count(),
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_donations' => Donation::count(),
            'total_raised' => Donation::where('status', 'completed')->sum('amount'),
        ];

        $recentCampaigns = Campaign::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentDonations = Donation::with(['user', 'campaign'])
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentCampaigns' => $recentCampaigns,
            'recentDonations' => $recentDonations,
        ]);
    }
}
