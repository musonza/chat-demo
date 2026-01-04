<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    conversation: Object,
    participants: Array,
    onlineUsers: Array,
});

const emit = defineEmits(['toggle-participants']);

const displayName = computed(() => {
    if (props.conversation?.data?.name) {
        return props.conversation.data.name;
    }
    return `Conversation #${props.conversation?.id}`;
});

const participantCount = computed(() => {
    return props.participants?.length || 0;
});

const onlineCount = computed(() => {
    return props.onlineUsers?.length || 0;
});

const isPrivate = computed(() => {
    return props.conversation?.private !== false;
});

const isDirect = computed(() => {
    return props.conversation?.direct_message === true;
});
</script>

<template>
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-white">
        <div class="flex items-center gap-3">
            <!-- Back button for mobile -->
            <Link
                :href="route('chat.index')"
                class="lg:hidden p-1 text-gray-400 hover:text-gray-600"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </Link>

            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ displayName }}</h2>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>{{ participantCount }} participant{{ participantCount !== 1 ? 's' : '' }}</span>
                    <span v-if="onlineCount > 0" class="flex items-center text-green-600">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        {{ onlineCount }} online
                    </span>
                    <span v-if="!isPrivate" class="flex items-center text-indigo-600">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        Public
                    </span>
                    <span v-if="isDirect" class="text-gray-400">
                        Direct Message
                    </span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button
                @click="emit('toggle-participants')"
                class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
                title="Toggle participants"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
        </div>
    </div>
</template>
