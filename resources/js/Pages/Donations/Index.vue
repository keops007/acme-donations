<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    donations: Array,
});
</script>

<template>
    <Head title="My Donations" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Donations
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="donations.length > 0" class="space-y-4">
                            <div
                                v-for="donation in donations"
                                :key="donation.id"
                                class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
                            >
                                <div>
                                    <Link
                                        :href="route('campaigns.show', donation.campaign.id)"
                                        class="font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        {{ donation.campaign.title }}
                                    </Link>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ new Date(donation.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                    ${{ donation.amount }}
                                </span>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                You haven't made any donations yet.
                            </p>
                            <Link
                                href="/"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                Browse campaigns &rarr;
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
