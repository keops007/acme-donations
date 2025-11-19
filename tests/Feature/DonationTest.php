<?php

use App\Models\Campaign;
use App\Models\User;

test('user can donate to active campaign', function () {
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create(['status' => 'active', 'current_amount' => 0]);

    $response = $this->actingAs($user)->post(route('donations.store', $campaign), [
        'amount' => 50,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('donations', [
        'campaign_id' => $campaign->id,
        'user_id' => $user->id,
        'amount' => 50,
        'status' => 'completed',
    ]);

    $campaign->refresh();
    expect($campaign->current_amount)->toBe('50.00');
});

test('cannot donate to inactive campaign', function () {
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create(['status' => 'completed']);

    $response = $this->actingAs($user)->post(route('donations.store', $campaign), [
        'amount' => 50,
    ]);

    $this->assertDatabaseMissing('donations', [
        'campaign_id' => $campaign->id,
        'user_id' => $user->id,
    ]);
});

test('campaign completes when goal is reached', function () {
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create([
        'status' => 'active',
        'goal_amount' => 100,
        'current_amount' => 80,
    ]);

    $this->actingAs($user)->post(route('donations.store', $campaign), [
        'amount' => 30,
    ]);

    $campaign->refresh();
    expect($campaign->status)->toBe('completed');
});
