<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Donation settings
            [
                'key' => 'min_donation_amount',
                'value' => '1',
                'type' => 'integer',
                'group' => 'donations',
                'label' => 'Minimum Donation Amount',
                'description' => 'Minimum amount allowed for a single donation (in dollars)',
            ],
            [
                'key' => 'max_donation_amount',
                'value' => '10000',
                'type' => 'integer',
                'group' => 'donations',
                'label' => 'Maximum Donation Amount',
                'description' => 'Maximum amount allowed for a single donation (in dollars)',
            ],

            // Campaign settings
            [
                'key' => 'max_campaign_goal',
                'value' => '100000',
                'type' => 'integer',
                'group' => 'campaigns',
                'label' => 'Maximum Campaign Goal',
                'description' => 'Maximum goal amount allowed for a campaign (in dollars)',
            ],
            [
                'key' => 'featured_campaigns_count',
                'value' => '6',
                'type' => 'integer',
                'group' => 'campaigns',
                'label' => 'Featured Campaigns Count',
                'description' => 'Number of campaigns to display on the homepage',
            ],
            [
                'key' => 'require_campaign_approval',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'campaigns',
                'label' => 'Require Campaign Approval',
                'description' => 'If enabled, campaigns must be approved by an admin before being published',
            ],

            // General settings
            [
                'key' => 'site_name',
                'value' => 'ACME Donations',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'The name of the donation platform',
            ],
            [
                'key' => 'support_email',
                'value' => 'support@acme-donations.life',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Support Email',
                'description' => 'Contact email for user support',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
