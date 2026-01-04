<script setup>
import { ref, nextTick, watch } from 'vue';
import MessageItem from './MessageItem.vue';

const props = defineProps({
    messages: Array,
    currentUser: Object,
});

const containerRef = ref(null);

const scrollToBottom = () => {
    if (containerRef.value) {
        containerRef.value.scrollTop = containerRef.value.scrollHeight;
    }
};

const isOwnMessage = (message) => {
    return message.sender?.id === props.currentUser?.id &&
           message.sender?.type === props.currentUser?.type;
};

const shouldShowSender = (message, index) => {
    if (index === 0) return true;
    const prevMessage = props.messages[index - 1];
    return prevMessage.sender?.id !== message.sender?.id ||
           prevMessage.sender?.type !== message.sender?.type;
};

watch(() => props.messages?.length, () => {
    nextTick(scrollToBottom);
});

defineExpose({ scrollToBottom });
</script>

<template>
    <div
        ref="containerRef"
        class="flex-1 overflow-y-auto px-4 py-4 space-y-1 bg-gray-50"
    >
        <div v-if="!messages || messages.length === 0" class="flex items-center justify-center h-full">
            <div class="text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="mt-2 text-sm">No messages yet. Start the conversation!</p>
            </div>
        </div>
        <MessageItem
            v-for="(message, index) in messages"
            :key="message.id"
            :message="message"
            :is-own="isOwnMessage(message)"
            :show-sender="shouldShowSender(message, index)"
        />
    </div>
</template>
