<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@acme-donations.life')->first();

        if (!$admin) {
            return;
        }

        $campaigns = [
            [
                'title' => 'Community Garden Project',
                'description' => 'Looking to transform the empty lot on 5th street into a community garden. We need funds for soil, seeds, tools and a small shed. Volunteers welcome!',
                'goal_amount' => 5000,
                'current_amount' => 1250,
            ],
            [
                'title' => 'Back to School Drive',
                'description' => 'Collecting funds to buy backpacks and school supplies for kids from low-income families in our district. Last year we helped 150 students.',
                'goal_amount' => 2000,
                'current_amount' => 800,
            ],
            [
                'title' => 'Water Filters for Rural Schools',
                'description' => 'Installing water filtration systems in 3 rural schools that currently lack access to clean drinking water.',
                'goal_amount' => 10000,
                'current_amount' => 10000,
                'status' => 'completed',
            ],
            [
                'title' => 'Youth Center Computer Lab',
                'description' => 'The downtown youth center needs 10 computers for their after-school program. Kids use them for homework and learning to code.',
                'goal_amount' => 7500,
                'current_amount' => 3200,
            ],
            [
                'title' => 'Greenfield Animal Shelter Repairs',
                'description' => 'The shelter roof is leaking and the heating system needs replacement before winter. Any amount helps!',
                'goal_amount' => 15000,
                'current_amount' => 4500,
            ],
            [
                'title' => 'Meals on Wheels Expansion',
                'description' => 'Expanding our meal delivery routes to reach 50 more seniors who live alone and have difficulty preparing food.',
                'goal_amount' => 3000,
                'current_amount' => 1800,
            ],
        ];

        foreach ($campaigns as $data) {
            Campaign::create(array_merge([
                'user_id' => $admin->id,
                'status' => 'active',
            ], $data));
        }
    }
}
