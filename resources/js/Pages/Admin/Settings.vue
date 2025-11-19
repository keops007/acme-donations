<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

// Flatten settings for form
const initialValues = {};
Object.values(props.settings).flat().forEach(setting => {
    initialValues[setting.key] = setting.value || '';
});

const form = useForm({
    settings: initialValues,
});

const submit = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Application Settings
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div v-for="(groupSettings, groupName) in settings" :key="groupName" class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100 capitalize">
                            {{ groupName }} Settings
                        </h3>

                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 space-y-6">
                                <div v-for="setting in groupSettings" :key="setting.key">
                                    <label :for="setting.key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ setting.label }}
                                    </label>
                                    <p v-if="setting.description" class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        {{ setting.description }}
                                    </p>

                                    <!-- Boolean toggle -->
                                    <div v-if="setting.type === 'boolean'" class="mt-1">
                                        <select
                                            :id="setting.key"
                                            v-model="form.settings[setting.key]"
                                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <!-- Number input -->
                                    <input
                                        v-else-if="setting.type === 'integer' || setting.type === 'float'"
                                        :id="setting.key"
                                        type="number"
                                        v-model="form.settings[setting.key]"
                                        :step="setting.type === 'float' ? '0.01' : '1'"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />

                                    <!-- Text input -->
                                    <input
                                        v-else
                                        :id="setting.key"
                                        type="text"
                                        v-model="form.settings[setting.key]"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="Object.keys(settings).length === 0" class="text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">No settings configured yet.</p>
                    </div>

                    <div v-else class="flex justify-end">
                        <PrimaryButton :disabled="form.processing">
                            Save Settings
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
