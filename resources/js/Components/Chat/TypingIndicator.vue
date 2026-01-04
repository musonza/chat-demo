<script setup>
import { computed } from 'vue';

const props = defineProps({
    users: Array,
});

const typingText = computed(() => {
    if (!props.users || props.users.length === 0) return '';

    const names = props.users.map(u => u.name);
    if (names.length === 1) return `${names[0]} is typing...`;
    if (names.length === 2) return `${names[0]} and ${names[1]} are typing...`;
    return `${names[0]} and ${names.length - 1} others are typing...`;
});
</script>

<template>
    <div
        v-if="users && users.length > 0"
        class="px-4 py-2 bg-white border-t border-gray-100"
    >
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <div class="flex space-x-1">
                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
            </div>
            <span>{{ typingText }}</span>
        </div>
    </div>
</template>
