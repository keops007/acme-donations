<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DonationController extends Controller
{
    public function index(): Response
    {
        $donations = Donation::with('campaign')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return Inertia::render('Donations/Index', [
            'donations' => $donations,
        ]);
    }

    public function store(Request $request, Campaign $campaign): RedirectResponse
    {
        if ($campaign->status !== 'active') {
            return back()->with('error', 'This campaign is no longer accepting donations.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($campaign, $validated) {
            Donation::create([
                'campaign_id' => $campaign->id,
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'status' => 'completed',
                'payment_method' => 'direct',
            ]);

            $campaign->increment('current_amount', $validated['amount']);

            if ($campaign->current_amount >= $campaign->goal_amount) {
                $campaign->update(['status' => 'completed']);
            }
        });

        return back()->with('success', 'Thank you for your donation!');
    }
}
