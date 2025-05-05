<script setup>

</script>

<template>
    <section class="relative flex min-h-screen flex-col items-center justify-center px-4 py-12 text-center">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white to-gray-100"></div>
        <!-- Logo -->
        <div class="mb-8 flex items-center justify-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-600 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-mountain-snow">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                    <path
                        d="M4.14 15.08c2.62-1.57 5.24 1.41 5.24 1.41 2.62-1.57 5.24 1.41 5.24 1.41 2.62-1.57 5.24 1.41 5.24 1.41"/>
                </svg>
            </div>
            <h1 class="ml-3 text-2xl font-bold text-gray-900">I-Task</h1>
        </div>

        <!-- Hero Content -->
        <div class="max-w-3xl">
            <h2 class="mb-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                Stay focused, stay organized
            </h2>
            <p class="mb-8 text-xl text-gray-600">
                Your all-in-one task manager for everyday productivity.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col items-center justify-center space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
                <router-link
                    to="/login"
                    class="rounded-lg bg-emerald-600 px-6 py-3 text-base font-medium text-white shadow-sm transition-colors hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    Sign In
                </router-link>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
