<script setup>
import {useAppStore} from "../store.js";
import {storeToRefs} from "pinia";
import Landing from "../components/Landing.vue";
import Task from "./Task.vue";

const store = useAppStore()
const {isLoggedIn} = storeToRefs(store)
</script>

<template>
    <Task v-if="isLoggedIn"/>
    <Landing v-else/>
</template>

<style scoped>

</style>
