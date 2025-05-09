<script setup>
import Navbar from "./pages/Navbar.vue";
</script>

<template>
    <div class="min-h-[calc(100vh-64px)] bg-gray-50">
        <Navbar/>
        <router-view/>
    </div>
</template>

<style scoped>

</style>
