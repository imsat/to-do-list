<script setup>
import {defineProps} from 'vue';

const props = defineProps({
    errorText: String
});
</script>

<template>
    <span v-if="errorText" class="text-red-500 text-sm" v-html="errorText"></span>
</template>

<style scoped>
</style>
