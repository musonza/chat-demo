<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import ChatLayout from '@/Layouts/ChatLayout.vue';
import ConversationList from '@/Components/Chat/ConversationList.vue';
import MessageList from '@/Components/Chat/MessageList.vue';
import MessageInput from '@/Components/Chat/MessageInput.vue';
import ConversationHeader from '@/Components/Chat/ConversationHeader.vue';
import ParticipantList from '@/Components/Chat/ParticipantList.vue';
import TypingIndicator from '@/Components/Chat/TypingIndicator.vue';
import NewConversationModal from '@/Components/Chat/NewConversationModal.vue';
import { useRealtime } from '@/Composables/useRealtime';
import { useMessages } from '@/Composables/useMessages';

const props = defineProps({
    conversation: Object,
    messages: Object,
    participants: Array,
    currentUser: Object,
    conversations: Object,
    users: Array,
    bots: Array,
});

const messageListRef = ref(null);
const showParticipants = ref(false);
const showNewConversationModal = ref(false);
const localMessages = ref([]);

const conversationId = computed(() => props.conversation?.id);

const {
    realtimeMessages,
    typingUsers,
    onlineUsers,
    sendTypingIndicator,
} = useRealtime(conversationId);

const { sendMessage, isSending, markAsRead } = useMessages(conversationId);

const allMessages = computed(() => {
    const initial = props.messages?.data || [];
    // Combine initial messages, locally sent messages, and realtime messages
    // Filter out duplicates by id
    const combined = [...initial, ...localMessages.value, ...realtimeMessages.value];
    const seen = new Set();
    return combined.filter(msg => {
        if (seen.has(msg.id)) return false;
        seen.add(msg.id);
        return true;
    });
});

const handleSendMessage = async (body) => {
    try {
        const message = await sendMessage(body);
        if (message) {
            // Add message to local state for immediate feedback
            localMessages.value.push(message);
        }
        nextTick(() => {
            messageListRef.value?.scrollToBottom();
        });
    } catch (error) {
        console.error('Failed to send message:', error);
    }
};

const handleTyping = () => {
    sendTypingIndicator(props.currentUser);
};

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

onMounted(() => {
    markAsRead();
    nextTick(() => {
        messageListRef.value?.scrollToBottom();
    });
});

watch(conversationId, () => {
    // Clear local messages when switching conversations
    localMessages.value = [];
    markAsRead();
    nextTick(() => {
        messageListRef.value?.scrollToBottom();
    });
});
</script>

<template>
    <Head :title="`Chat - ${conversation?.data?.name || 'Conversation'}`" />

    <ChatLayout>
        <div class="flex h-full">
            <!-- Conversation Sidebar -->
            <aside class="w-80 bg-white border-r border-gray-200 flex-col hidden lg:flex">
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
                    :active-id="conversation?.id"
                    @select="selectConversation"
                />
            </aside>

            <!-- Main Chat Area -->
            <main class="flex-1 flex flex-col bg-white">
                <ConversationHeader
                    :conversation="conversation"
                    :participants="participants"
                    :online-users="onlineUsers"
                    @toggle-participants="showParticipants = !showParticipants"
                />

                <MessageList
                    ref="messageListRef"
                    :messages="allMessages"
                    :current-user="currentUser"
                />

                <TypingIndicator :users="typingUsers" />

                <MessageInput
                    :is-sending="isSending"
                    @send="handleSendMessage"
                    @typing="handleTyping"
                />
            </main>

            <!-- Participants Sidebar -->
            <aside
                v-if="showParticipants"
                class="w-64 bg-gray-50 border-l border-gray-200"
            >
                <ParticipantList
                    :participants="participants"
                    :online-users="onlineUsers"
                    :current-user="currentUser"
                />
            </aside>
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
