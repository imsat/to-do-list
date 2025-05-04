<script setup>
import {useAppStore} from "../store.js";
import {useRouter} from 'vue-router';
import {storeToRefs} from "pinia";
import Validation from "../components/Validation.vue";

const router = useRouter();
const store = useAppStore()
const {registerForm} = storeToRefs(store)
</script>

<template>
    <div class="flex justify-center items-center min-h-[calc(100vh-64px)]">
        <div class="relative w-full max-w-md rounded-lg bg-white p-8 shadow-xl ">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Create an account</h2>
                <p class="text-sm text-gray-600">Sign up to get started</p>
            </div>

            <form @submit.prevent="store.signUp" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        id="name"
                        v-model="registerForm.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500"
                        placeholder="Name"
                    />
                    <Validation :error-text="store.getError('name')"/>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        v-model="registerForm.email"
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
                        v-model="registerForm.password"
                        type="password"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-emerald-500"
                        placeholder="••••••••"
                    />
                    <Validation :error-text="store.getError('password')"/>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    Sign up
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Already have an account?
                <router-link to="/login" class="font-medium text-emerald-600 hover:text-emerald-500">
                    Sign in
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
