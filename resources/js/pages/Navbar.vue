<script setup>
import {storeToRefs} from 'pinia'
import {onMounted, onUnmounted, ref} from "vue";
import {useAppStore} from "../store.js";

const store = useAppStore()
const {isLoggedIn, user } = storeToRefs(store)
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)
const menuRef = ref(null);
function handleOutsideClick(event) {
    if (menuRef.value && !menuRef.value.contains(event.target)) {
        userMenuOpen.value = false;
    }
}

onMounted(() => {
    document.addEventListener('click', handleOutsideClick);
});

onUnmounted(() => {
    document.removeEventListener('click', handleOutsideClick);
});
</script>

<template>
    <nav class="bg-white shadow-sm" ref="menuRef">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <router-link to="/" class="flex flex-shrink-0 items-center" @click="">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mountain-snow"><path d="m8 3 4 8 5-5 5 15H2L8 3z"/><path d="M4.14 15.08c2.62-1.57 5.24 1.41 5.24 1.41 2.62-1.57 5.24 1.41 5.24 1.41 2.62-1.57 5.24 1.41 5.24 1.41"/></svg>
                        </div>
                        <h1 class="ml-2 text-xl font-bold text-gray-900">Spire</h1>
                    </router-link>

                    <!-- Navigation Links (only visible when logged in) -->
                    <div v-if="isLoggedIn" class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="#" class="inline-flex items-center border-b-2 border-emerald-500 px-1 pt-1 text-sm font-medium text-gray-900">Dashboard</a>
                        <a href="#" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Tasks</a>
                        <a href="#" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Team</a>
                        <a href="#" class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">Reports</a>
                    </div>
                </div>

                <!-- Right side buttons -->
                <div class="flex items-center">
                    <div v-if="!isLoggedIn" class="flex space-x-4">
                        <router-link
                            to="/login"
                            class="rounded-md bg-white px-3 py-2 text-sm font-medium text-emerald-600 hover:bg-gray-50"
                        >
                            Sign in
                        </router-link>
                        <router-link
                            to="/register"
                            class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            Sign up
                        </router-link>
                    </div>

                    <!-- User dropdown (only visible when logged in) -->
                    <div v-if="isLoggedIn" class="relative ml-3">
                        <div>
                            <button
                                @click="userMenuOpen = !userMenuOpen"
                                type="button"
                                class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                id="user-menu-button"
                            >
                                <span class="sr-only">Open user menu</span>
                                <div class="flex items-center">
                                    <img
                                        class="h-8 w-8 rounded-full"
                                        src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                        alt="User profile"
                                    />
                                    <span class="ml-2 hidden text-sm font-medium text-gray-700 md:block">{{ user?.name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down ml-1"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </button>
                        </div>

                        <!-- Dropdown menu -->
                        <div
                            v-if="userMenuOpen"
                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu"
                            aria-orientation="vertical"
                            aria-labelledby="user-menu-button"
                        >
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                            <button
                                @click="store.logout"
                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem"
                            >
                                Sign out
                            </button>
                        </div>
                    </div>

                    <!-- Mobile menu button -->
                    <div v-if="isLoggedIn" class="-mr-2 ml-6 flex items-center sm:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            type="button"
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500"
                            aria-controls="mobile-menu"
                            aria-expanded="false"
                        >
                            <span class="sr-only">Open main menu</span>
                            <svg
                                v-if="!mobileMenuOpen"
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-menu"
                            >
                                <line x1="4" x2="20" y1="12" y2="12"/>
                                <line x1="4" x2="20" y1="6" y2="6"/>
                                <line x1="4" x2="20" y1="18" y2="18"/>
                            </svg>
                            <svg
                                v-else
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-x"
                            >
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state -->
        <div v-if="isLoggedIn && mobileMenuOpen" class="sm:hidden" id="mobile-menu">
            <div class="space-y-1 pb-3 pt-2">
                <a href="#" class="block border-l-4 border-emerald-500 bg-emerald-50 py-2 pl-3 pr-4 text-base font-medium text-emerald-700">Dashboard</a>
                <a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800">Projects</a>
                <a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800">Team</a>
                <a href="#" class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800">Reports</a>
            </div>
            <div class="border-t border-gray-200 pb-3 pt-4">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img
                            class="h-10 w-10 rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                            alt="User profile"
                        />
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ user?.name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ user?.email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Your Profile</a>
                    <a href="#" class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800">Settings</a>
                    <button
                        @click="store.logout"
                        class="block w-full px-4 py-2 text-left text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800"
                    >
                        Sign out
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>

</style>
