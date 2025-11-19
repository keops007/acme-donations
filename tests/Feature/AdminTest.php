<?php

use App\Models\User;

test('admin can access admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Admin/Dashboard'));
});

test('non-admin cannot access admin dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

test('guest cannot access admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});
