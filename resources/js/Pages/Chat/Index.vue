<script setup>
import { ref, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import ChatLayout from '@/Layouts/ChatLayout.vue';
import ConversationList from '@/Components/Chat/ConversationList.vue';
import NewConversationModal from '@/Components/Chat/NewConversationModal.vue';

const props = defineProps({
    conversations: Object,
    currentUser: Object,
    users: Array,
    bots: Array,
});

const showNewConversationModal = ref(false);

const selectConversation = (conversation) => {
    router.visit(route('chat.show', conversation.id), {
        preserveState: false,
    });
};

const handleConversationCreated = (conversation) => {
    showNewConversationModal.value = false;
    router.visit(route('chat.show', conversation.id), {
        preserveState: false,
    });
};
</script>

<template>
    <Head title="Chat" />

    <ChatLayout>
        <div class="flex h-full">
            <!-- Sidebar -->
            <aside class="w-80 bg-white border-r border-gray-200 flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Messages</h2>
                        <button
                            @click="showNewConversationModal = true"
                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>

                <ConversationList
                    :conversations="conversations?.data || []"
                    :current-user="currentUser"
                    @select="selectConversation"
                />
            </aside>

            <!-- Empty State -->
            <main class="flex-1 flex items-center justify-center bg-gray-50">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Select a conversation</h3>
                    <p class="mt-2 text-sm text-gray-500">Choose a conversation from the sidebar or start a new one</p>
                    <button
                        @click="showNewConversationModal = true"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Conversation
                    </button>
                </div>
            </main>
        </div>

        <NewConversationModal
            :show="showNewConversationModal"
            :users="users"
            :bots="bots"
            @close="showNewConversationModal = false"
            @created="handleConversationCreated"
        />
    </ChatLayout>
</template>
