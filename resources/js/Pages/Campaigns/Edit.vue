<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    campaign: Object,
});

const form = useForm({
    title: props.campaign.title,
    description: props.campaign.description,
    goal_amount: props.campaign.goal_amount,
    status: props.campaign.status,
});

const submit = () => {
    form.put(route('campaigns.update', props.campaign.id));
};

const deleteCampaign = () => {
    if (confirm('Are you sure you want to delete this campaign?')) {
        router.delete(route('campaigns.destroy', props.campaign.id));
    }
};
</script>

<template>
    <Head title="Edit Campaign" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="title" value="Campaign Title" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="description" value="Description" />
                                <textarea
                                    id="description"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    v-model="form.description"
                                    rows="5"
                                    required
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div>
                                <InputLabel for="goal_amount" value="Goal Amount ($)" />
                                <TextInput
                                    id="goal_amount"
                                    type="number"
                                    step="0.01"
                                    min="1"
                                    class="mt-1 block w-full"
                                    v-model="form.goal_amount"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.goal_amount" />
                            </div>

                            <div>
                                <InputLabel for="status" value="Status" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div class="flex items-center justify-between">
                                <DangerButton type="button" @click="deleteCampaign">
                                    Delete Campaign
                                </DangerButton>
                                <PrimaryButton :disabled="form.processing">
                                    Update Campaign
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
