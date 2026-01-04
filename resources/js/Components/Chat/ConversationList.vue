<script setup>
import { computed } from 'vue';
import ConversationItem from './ConversationItem.vue';

const props = defineProps({
    conversations: Array,
    currentUser: Object,
    activeId: [Number, String],
});

const emit = defineEmits(['select']);

const sortedConversations = computed(() => {
    if (!props.conversations) return [];
    return [...props.conversations].sort((a, b) => {
        const aDate = new Date(a.last_message?.created_at || a.updated_at);
        const bDate = new Date(b.last_message?.created_at || b.updated_at);
        return bDate - aDate;
    });
});
</script>

<template>
    <div class="flex-1 overflow-y-auto">
        <div v-if="sortedConversations.length === 0" class="p-4 text-center text-gray-500 text-sm">
            No conversations yet
        </div>
        <ConversationItem
            v-for="conversation in sortedConversations"
            :key="conversation.id"
            :conversation="conversation"
            :current-user="currentUser"
            :is-active="activeId === conversation.id"
            @click="emit('select', conversation)"
        />
    </div>
</template>
