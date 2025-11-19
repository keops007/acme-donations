<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';

const props = defineProps({
    campaigns: Object,
    filters: Object,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

watch([search, status], ([newSearch, newStatus]) => {
    router.get(route('campaigns.index'), {
        search: newSearch || undefined,
        status: newStatus || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
}, { debounce: 300 });

const progressPercentage = (campaign) => {
    return Math.min(100, (campaign.current_amount / campaign.goal_amount) * 100);
};
</script>

<template>
    <Head title="Campaigns" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Campaigns
                </h2>
                <Link
                    v-if="user"
                    :href="route('campaigns.create')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Create Campaign
                </Link>
                <Link
                    v-else
                    :href="route('login')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Login to Create
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="mb-6 flex gap-4">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search campaigns..."
                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                    <select
                        v-model="status"
                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Campaigns Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="campaign in campaigns.data"
                        :key="campaign.id"
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                    >
                        <div class="p-6">
                            <Link :href="route('campaigns.show', campaign.id)">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ campaign.title }}
                                </h3>
                            </Link>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                {{ campaign.description }}
                            </p>

                            <!-- Progress Bar -->
                            <div class="mb-2">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">
                                        ${{ campaign.current_amount }} raised
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-400">
                                        ${{ campaign.goal_amount }} goal
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div
                                        class="bg-blue-600 h-2 rounded-full"
                                        :style="{ width: progressPercentage(campaign) + '%' }"
                                    ></div>
                                </div>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">
                                    by {{ campaign.user.name }}
                                </span>
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

                <!-- Empty State -->
                <div v-if="campaigns.data.length === 0" class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No campaigns found.</p>
                </div>

                <!-- Pagination -->
                <div v-if="campaigns.links && campaigns.links.length > 3" class="mt-6 flex justify-center gap-2">
                    <Link
                        v-for="link in campaigns.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        class="px-3 py-1 rounded"
                        :class="{
                            'bg-blue-500 text-white': link.active,
                            'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300': !link.active && link.url,
                            'text-gray-400 cursor-not-allowed': !link.url,
                        }"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
