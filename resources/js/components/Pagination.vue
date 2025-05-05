<template>
    <div class="mt-6 flex items-center justify-between">
        <button
            @click="emitPaginate(prevCursor)"
            :disabled="!prevCursor"
            class="flex items-center rounded-md px-3 py-1 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-chevron-left mr-1">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            Previous
        </button>

        <button
            @click="emitPaginate(nextCursor)"
            :disabled="!nextCursor"
            class="flex items-center rounded-md px-3 py-1 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer"
        >
            Next
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-chevron-right ml-1">
                <path d="m9 18 6-6-6-6"/>
            </svg>
        </button>
    </div>
</template>

<script setup>
import {defineProps} from 'vue';

const props = defineProps({
    prevCursor: {
        type: String,
        default: null
    },
    nextCursor: {
        type: String,
        default: null
    }
});

const emit = defineEmits(['paginate']);

const emitPaginate = (cursor) => {
    emit('paginate', cursor);
};
</script>
