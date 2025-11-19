<?php

use App\Models\Campaign;
use App\Models\User;

test('campaigns index displays campaigns', function () {
    $user = User::factory()->create();
    Campaign::factory()->count(3)->create();

    $response = $this->actingAs($user)->get(route('campaigns.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Campaigns/Index'));
});

test('authenticated user can create campaign', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('campaigns.store'), [
        'title' => 'Test Campaign',
        'description' => 'Test Description',
        'goal_amount' => 1000,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('campaigns', [
        'title' => 'Test Campaign',
        'user_id' => $user->id,
    ]);
});

test('campaign owner can update campaign', function () {
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->put(route('campaigns.update', $campaign), [
        'title' => 'Updated Title',
        'description' => 'Updated Description',
        'goal_amount' => 2000,
        'status' => 'active',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('campaigns', [
        'id' => $campaign->id,
        'title' => 'Updated Title',
    ]);
});

test('non-owner cannot update campaign', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $campaign = Campaign::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($other)->put(route('campaigns.update', $campaign), [
        'title' => 'Hacked Title',
        'description' => 'Hacked Description',
        'goal_amount' => 2000,
        'status' => 'active',
    ]);

    $response->assertStatus(403);
});

test('admin can update any campaign', function () {
    $owner = User::factory()->create();
    $admin = User::factory()->create(['is_admin' => true]);
    $campaign = Campaign::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($admin)->put(route('campaigns.update', $campaign), [
        'title' => 'Admin Updated',
        'description' => 'Admin Description',
        'goal_amount' => 2000,
        'status' => 'active',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('campaigns', [
        'id' => $campaign->id,
        'title' => 'Admin Updated',
    ]);
});

test('campaign can be searched', function () {
    $user = User::factory()->create();
    Campaign::factory()->create(['title' => 'Unique Search Term']);
    Campaign::factory()->create(['title' => 'Other Campaign']);

    $response = $this->actingAs($user)->get(route('campaigns.index', ['search' => 'Unique']));

    $response->assertStatus(200);
});
