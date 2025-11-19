<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    campaigns: Array,
    canLogin: Boolean,
    canRegister: Boolean,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const progressPercentage = (campaign) => {
    return Math.min(100, (campaign.current_amount / campaign.goal_amount) * 100);
};
</script>

<template>
    <Head title="ACME Donations" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <Link href="/">
                        <img src="/logo.png" alt="ACME Donations" class="h-14 w-auto" />
                    </Link>
                    <nav class="flex items-center space-x-4">
                        <template v-if="user">
                            <Link
                                :href="route('donations.index')"
                                class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                            >
                                My Donations
                            </Link>
                            <span class="text-sm text-gray-600 dark:text-gray-300">
                                {{ user.name }}
                            </span>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                            >
                                Log out
                            </Link>
                        </template>
                        <template v-else-if="canLogin">
                            <Link
                                :href="route('login')"
                                class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="text-sm bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <div class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Support Causes That Matter
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Join your colleagues in making a difference. Browse campaigns and donate to causes you believe in.
                </p>
            </div>
        </div>

        <!-- Campaigns -->
        <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">
                Active Campaigns
            </h2>

            <div v-if="campaigns.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="campaign in campaigns"
                    :key="campaign.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden"
                >
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            {{ campaign.title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                            {{ campaign.description }}
                        </p>

                        <!-- Progress -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ${{ campaign.current_amount }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    of ${{ campaign.goal_amount }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div
                                    class="bg-blue-600 h-2 rounded-full"
                                    :style="{ width: progressPercentage(campaign) + '%' }"
                                ></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                by {{ campaign.user.name }}
                            </span>
                            <Link
                                :href="route('campaigns.show', campaign.id)"
                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                View &rarr;
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No campaigns yet.</p>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                    ACME Corp Donations Platform
                    <span v-if="user && user.is_admin" class="mx-2">|</span>
                    <Link
                        v-if="user && user.is_admin"
                        :href="route('admin.dashboard')"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400"
                    >
                        Admin
                    </Link>
                </p>
            </div>
        </footer>
    </div>
</template>
