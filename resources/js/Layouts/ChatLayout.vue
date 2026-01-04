<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="flex h-screen flex-col bg-gray-100">
        <nav class="border-b border-gray-100 bg-white flex-shrink-0">
            <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex h-14 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('chat.index')">
                                <ApplicationLogo class="block h-8 w-auto fill-current text-gray-800" />
                            </Link>
                            <span class="ml-3 text-lg font-semibold text-gray-900">Chat Demo</span>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <NavLink :href="route('chat.index')" :active="route().current('chat.index')">
                                Messages
                            </NavLink>
                            <NavLink :href="route('chat.discover')" :active="route().current('chat.discover')">
                                Discover
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                        >
                                            {{ $page.props.auth.user.name }}
                                            <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <Link :href="route('chat.index')" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Messages</Link>
                    <Link :href="route('chat.discover')" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Discover</Link>
                </div>
                <div class="border-t border-gray-200 pb-1 pt-4">
                    <div class="px-4">
                        <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <Link :href="route('profile.edit')" class="block px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Profile</Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50">Log Out</Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 overflow-hidden">
            <slot />
        </main>
    </div>
</template>
