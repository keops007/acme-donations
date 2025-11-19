<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    campaign: Object,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const canEdit = computed(() => {
    return user.value && (user.value.is_admin || user.value.id === props.campaign.user_id);
});

const progressPercentage = computed(() => {
    return Math.min(100, (props.campaign.current_amount / props.campaign.goal_amount) * 100);
});

const donationForm = useForm({
    amount: '',
});

const submitDonation = () => {
    donationForm.post(route('donations.store', props.campaign.id), {
        onSuccess: () => {
            donationForm.reset();
        },
    });
};
</script>

<template>
    <Head :title="campaign.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ campaign.title }}
                </h2>
                <Link
                    v-if="canEdit"
                    :href="route('campaigns.edit', campaign.id)"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Edit
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Campaign Info -->
                        <div class="mb-6">
                            <p class="text-gray-600 dark:text-gray-400 mb-4 whitespace-pre-line">
                                {{ campaign.description }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Created by {{ campaign.user.name }}
                            </p>
                        </div>

                        <!-- Progress -->
                        <div class="mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    ${{ campaign.current_amount }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">
                                    of ${{ campaign.goal_amount }} goal
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                <div
                                    class="bg-blue-600 h-4 rounded-full transition-all"
                                    :style="{ width: progressPercentage + '%' }"
                                ></div>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                {{ Math.round(progressPercentage) }}% funded
                            </p>
                        </div>

                        <!-- Donation Form (logged in users) -->
                        <div v-if="campaign.status === 'active' && user" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                Make a Donation
                            </h3>
                            <form @submit.prevent="submitDonation" class="flex gap-4">
                                <input
                                    type="number"
                                    v-model="donationForm.amount"
                                    step="0.01"
                                    min="1"
                                    placeholder="Amount ($)"
                                    class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300"
                                    required
                                />
                                <PrimaryButton :disabled="donationForm.processing">
                                    Donate
                                </PrimaryButton>
                            </form>
                            <InputError class="mt-2" :message="donationForm.errors.amount" />
                        </div>

                        <!-- Login prompt (guests) -->
                        <div v-else-if="campaign.status === 'active' && !user" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                Make a Donation
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                Please log in to donate to this campaign.
                            </p>
                            <Link
                                :href="route('login')"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700"
                            >
                                Log in to Donate
                            </Link>
                        </div>

                        <!-- Status Badge -->
                        <div v-else-if="campaign.status !== 'active'" class="mb-6 p-4 rounded-lg" :class="{
                            'bg-blue-100 dark:bg-blue-900': campaign.status === 'completed',
                            'bg-red-100 dark:bg-red-900': campaign.status === 'cancelled',
                        }">
                            <p class="font-semibold" :class="{
                                'text-blue-800 dark:text-blue-200': campaign.status === 'completed',
                                'text-red-800 dark:text-red-200': campaign.status === 'cancelled',
                            }">
                                This campaign is {{ campaign.status }}
                            </p>
                        </div>

                        <!-- Recent Donations -->
                        <div v-if="campaign.donations && campaign.donations.length > 0">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                Recent Donations
                            </h3>
                            <div class="space-y-3">
                                <div
                                    v-for="donation in campaign.donations"
                                    :key="donation.id"
                                    class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded"
                                >
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{ donation.user.name }}
                                    </span>
                                    <span class="font-semibold text-green-600 dark:text-green-400">
                                        ${{ donation.amount }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Back link -->
                        <div class="mt-6 pt-6 border-t dark:border-gray-700">
                            <Link
                                href="/"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                            >
                                &larr; Back to campaigns
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
