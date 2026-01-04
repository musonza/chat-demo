<script setup>
import { computed } from 'vue';

const props = defineProps({
    conversation: Object,
    currentUser: Object,
    isActive: Boolean,
});

const emit = defineEmits(['click']);

const displayName = computed(() => {
    if (props.conversation.data?.name) {
        return props.conversation.data.name;
    }

    if (props.conversation.direct_message && props.conversation.participants) {
        const otherParticipant = props.conversation.participants.find(
            p => !(p.messageable_type?.includes('User') && p.messageable_id === props.currentUser?.id)
        );
        return otherParticipant?.messageable?.name || 'Direct Message';
    }

    return `Conversation #${props.conversation.id}`;
});

const lastMessage = computed(() => {
    return props.conversation.last_message?.body || 'No messages yet';
});

const lastMessageTime = computed(() => {
    if (!props.conversation.last_message?.created_at) return '';
    const date = new Date(props.conversation.last_message.created_at);
    const now = new Date();
    const diff = now - date;

    if (diff < 60000) return 'Just now';
    if (diff < 3600000) return `${Math.floor(diff / 60000)}m`;
    if (diff < 86400000) return `${Math.floor(diff / 3600000)}h`;
    return date.toLocaleDateString();
});

const avatarInitials = computed(() => {
    return displayName.value.substring(0, 2).toUpperCase();
});

const isBot = computed(() => {
    if (props.conversation.direct_message && props.conversation.participants) {
        return props.conversation.participants.some(p => p.messageable_type?.includes('Bot'));
    }
    return false;
});
</script>

<template>
    <div
        @click="emit('click')"
        :class="[
            'flex items-center gap-3 px-4 py-3 cursor-pointer transition-colors',
            isActive ? 'bg-indigo-50 border-r-2 border-indigo-600' : 'hover:bg-gray-50'
        ]"
    >
        <div :class="[
            'w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-medium flex-shrink-0',
            isBot ? 'bg-purple-500' : 'bg-indigo-500'
        ]">
            {{ avatarInitials }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium text-gray-900 truncate">
                    {{ displayName }}
                    <span v-if="isBot" class="ml-1 text-xs text-purple-600">(Bot)</span>
                </h3>
                <span class="text-xs text-gray-500">{{ lastMessageTime }}</span>
            </div>
            <p class="text-sm text-gray-500 truncate">{{ lastMessage }}</p>
        </div>
        <div v-if="conversation.unread_count > 0" class="flex-shrink-0">
            <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium text-white bg-indigo-600 rounded-full">
                {{ conversation.unread_count > 9 ? '9+' : conversation.unread_count }}
            </span>
        </div>
    </div>
</template>
