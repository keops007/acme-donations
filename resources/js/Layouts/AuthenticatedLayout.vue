<script setup>
import { ref, computed, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash);
const showFlash = ref(false);

watch(() => page.props.flash, (newFlash) => {
    if (newFlash?.success || newFlash?.error) {
        showFlash.value = true;
        setTimeout(() => {
            showFlash.value = false;
        }, 5000);
    }
}, { immediate: true });
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav
                class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link href="/">
                                    <img src="/logo.png" alt="ACME Donations" class="block h-14 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    href="/"
                                    :active="route().current('/')"
                                >
                                    Campaigns
                                </NavLink>
                                <NavLink
                                    v-if="user && user.is_admin"
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.dashboard')"
                                >
                                    Admin
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown (authenticated) -->
                            <div v-if="user" class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                            >
                                                {{ user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('donations.index')"
                                        >
                                            My Donations
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <!-- Guest links -->
                            <div v-else class="flex items-center space-x-4">
                                <Link
                                    :href="route('login')"
                                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100"
                                >
                                    Log in
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100"
                                >
                                    Register
                                </Link>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                    >
                        <template v-if="user">
                            <div class="px-4">
                                <div
                                    class="text-base font-medium text-gray-800 dark:text-gray-200"
                                >
                                    {{ user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ user.email }}
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')">
                                    Profile
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </template>
                        <template v-else>
                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('login')">
                                    Log in
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('register')">
                                    Register
                                </ResponsiveNavLink>
                            </div>
                        </template>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="showFlash && (flash?.success || flash?.error)" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
                <div
                    v-if="flash?.success"
                    class="p-4 rounded-md bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700"
                >
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ flash.success }}
                    </p>
                </div>
                <div
                    v-if="flash?.error"
                    class="p-4 rounded-md bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700"
                >
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ flash.error }}
                    </p>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
