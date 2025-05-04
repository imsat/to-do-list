<script setup>
import {useAppStore} from "../store.js";
import {useRouter} from 'vue-router';
import {storeToRefs} from "pinia";
import Validation from "../components/Validation.vue";

const router = useRouter();
const store = useAppStore()
const {loginForm} = storeToRefs(store)
</script>

<template>
    <div class="flex justify-center items-center min-h-[calc(100vh-64px)]">
        <div class="relative w-full max-w-md rounded-lg bg-white p-8 shadow-xl ">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                <p class="text-sm text-gray-600">Sign in to your account</p>
            </div>

            <form @submit.prevent="store.signIn" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        v-model="loginForm.email"
                        type="email"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500"
                        placeholder="you@example.com"
                    />
                    <Validation :error-text="store.getError('email')"/>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        v-model="loginForm.password"
                        type="password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500"
                        placeholder="••••••••"
                    />
                    <Validation :error-text="store.getError('password')"/>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember-me"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                        />
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>

                    <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-500">
                        Forgot password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    Sign in
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Don't have an account?
                <router-link to="/register" class="font-medium text-emerald-600 hover:text-emerald-500">
                    Sign up
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
