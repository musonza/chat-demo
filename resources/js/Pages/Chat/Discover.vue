<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import ChatLayout from '@/Layouts/ChatLayout.vue';

const props = defineProps({
    conversations: Object,
    currentUser: Object,
});

const joining = ref({});
const error = ref(null);

const joinConversation = async (conversationId) => {
    joining.value[conversationId] = true;
    error.value = null;

    try {
        // Use the configured axios instance from bootstrap.js (has withCredentials: true)
        await window.axios.post(`/api/conversations/${conversationId}/join`);
        // Force fresh page load to ensure all data is fetched
        router.visit(route('chat.show', conversationId), {
            preserveState: false,
            preserveScroll: false,
        });
    } catch (err) {
        console.error('Failed to join conversation:', err);
        error.value = err.response?.data?.message || 'Failed to join conversation. Please try again.';
        joining.value[conversationId] = false;
    }
};

const getParticipantCount = (conversation) => {
    return conversation.participants?.length || 0;
};
</script>

<template>
    <Head title="Discover Public Conversations" />

    <ChatLayout>
        <div class="h-full overflow-auto bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 py-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Discover Public Conversations</h1>
                    <p class="mt-2 text-gray-600">Join public chat rooms and connect with others</p>
                </div>

                <div v-if="conversations?.data?.length === 0" class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No public conversations</h3>
                    <p class="mt-2 text-sm text-gray-500">There are no public conversations available to join</p>
                </div>

                <div v-else class="grid gap-4 md:grid-cols-2">
                    <div
                        v-for="conversation in conversations?.data"
                        :key="conversation.id"
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ conversation.data?.name || `Conversation #${conversation.id}` }}
                                </h3>
                                <p v-if="conversation.data?.description" class="mt-1 text-sm text-gray-600">
                                    {{ conversation.data.description }}
                                </p>
                                <div class="mt-3 flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        {{ getParticipantCount(conversation) }} participants
                                    </span>
                                    <span class="flex items-center text-green-600">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        Public
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button
                                @click="joinConversation(conversation.id)"
                                :disabled="joining[conversation.id]"
                                class="w-full px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="joining[conversation.id]" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Joining...
                                </span>
                                <span v-else>Join Conversation</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ChatLayout>
</template>
