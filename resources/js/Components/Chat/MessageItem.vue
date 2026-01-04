<script setup>
import { computed } from 'vue';

const props = defineProps({
    message: Object,
    isOwn: Boolean,
    showSender: Boolean,
});

const formattedTime = computed(() => {
    if (!props.message.created_at) return '';
    const date = new Date(props.message.created_at);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
});

const senderType = computed(() => {
    return props.message.sender?.type || 'user';
});

const senderInitials = computed(() => {
    const name = props.message.sender?.name || 'U';
    return name.substring(0, 2).toUpperCase();
});
</script>

<template>
    <div
        :class="[
            'flex gap-3 py-1',
            isOwn ? 'flex-row-reverse' : 'flex-row'
        ]"
    >
        <!-- Avatar -->
        <div
            v-if="showSender && !isOwn"
            :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium flex-shrink-0',
                senderType === 'bot' ? 'bg-purple-500' : 'bg-indigo-500'
            ]"
        >
            {{ senderInitials }}
        </div>
        <div v-else-if="!isOwn" class="w-8 flex-shrink-0"></div>

        <!-- Message Bubble -->
        <div
            :class="[
                'max-w-[70%] rounded-2xl px-4 py-2',
                isOwn
                    ? 'bg-indigo-600 text-white rounded-br-md'
                    : 'bg-white text-gray-900 rounded-bl-md shadow-sm',
                senderType === 'bot' && !isOwn ? 'border-2 border-purple-200' : ''
            ]"
        >
            <!-- Sender Name -->
            <p
                v-if="showSender && !isOwn"
                :class="[
                    'text-xs font-medium mb-1',
                    senderType === 'bot' ? 'text-purple-600' : 'text-gray-500'
                ]"
            >
                {{ message.sender?.name }}
                <span v-if="senderType === 'bot'" class="ml-1 text-purple-400">(Bot)</span>
            </p>

            <!-- Message Body -->
            <p class="text-sm whitespace-pre-wrap break-words">{{ message.body }}</p>

            <!-- Timestamp -->
            <div class="flex items-center justify-end gap-1 mt-1">
                <span :class="[
                    'text-xs',
                    isOwn ? 'text-indigo-200' : 'text-gray-400'
                ]">
                    {{ formattedTime }}
                </span>
                <!-- Read indicator for own messages -->
                <svg
                    v-if="isOwn && message.is_seen"
                    class="w-3 h-3 text-indigo-200"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
    </div>
</template>
