<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recentCampaigns: Array,
    recentDonations: Array,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Admin Dashboard
                </h2>
                <Link
                    :href="route('admin.settings')"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Settings
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ stats.total_users }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Users</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ stats.total_campaigns }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Campaigns</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-3xl font-bold text-green-600">
                            {{ stats.active_campaigns }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Active Campaigns</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ stats.total_donations }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Donations</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-3xl font-bold text-blue-600">
                            ${{ Number(stats.total_raised).toLocaleString() }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Raised</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Campaigns -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                Recent Campaigns
                            </h3>
                            <div class="space-y-3">
                                <div
                                    v-for="campaign in recentCampaigns"
                                    :key="campaign.id"
                                    class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded"
                                >
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ campaign.title }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            by {{ campaign.user.name }}
                                        </div>
                                    </div>
                                    <span :class="{
                                        'text-green-600': campaign.status === 'active',
                                        'text-blue-600': campaign.status === 'completed',
                                        'text-red-600': campaign.status === 'cancelled',
                                    }">
                                        {{ campaign.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Donations -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                Recent Donations
                            </h3>
                            <div class="space-y-3">
                                <div
                                    v-for="donation in recentDonations"
                                    :key="donation.id"
                                    class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded"
                                >
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ donation.user.name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            to {{ donation.campaign.title }}
                                        </div>
                                    </div>
                                    <span class="font-semibold text-green-600">
                                        ${{ donation.amount }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
