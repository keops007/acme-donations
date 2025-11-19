<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CampaignController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Campaign::with('user')
            ->withCount('donations')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $campaigns = $query->paginate(12)->withQueryString();

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $campaigns,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Campaigns/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1',
        ]);

        $campaign = Auth::user()->campaigns()->create($validated);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully!');
    }

    public function show(Campaign $campaign): Response
    {
        $campaign->load(['user', 'donations.user']);

        return Inertia::render('Campaigns/Show', [
            'campaign' => $campaign,
        ]);
    }

    public function edit(Campaign $campaign): Response
    {
        $this->authorize('update', $campaign);

        return Inertia::render('Campaigns/Edit', [
            'campaign' => $campaign,
        ]);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal_amount' => 'required|numeric|min:1',
            'status' => 'required|in:active,completed,cancelled',
        ]);

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully!');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $this->authorize('delete', $campaign);

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully!');
    }
}
