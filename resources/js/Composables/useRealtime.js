import { ref, onMounted, onUnmounted, watch } from 'vue';

export function useRealtime(conversationId) {
    const realtimeMessages = ref([]);
    const onlineUsers = ref([]);
    const typingUsers = ref([]);
    let channel = null;
    let presenceChannel = null;
    let currentConversationId = null;

    const subscribeToConversation = (id) => {
        if (!id || !window.Echo) return;

        // Clean up existing subscriptions
        unsubscribe();

        currentConversationId = id;

        // Private channel for messages
        // Note: Leading dot tells Echo to use the full event class name
        channel = window.Echo.private(`mc-chat-conversation.${id}`)
            .listen('.Musonza\\Chat\\Eventing\\MessageWasSent', (e) => {
                console.log('Message received:', e);
                realtimeMessages.value.push(e.message);
            })
            .listen('.Musonza\\Chat\\Eventing\\ParticipantsJoined', (e) => {
                console.log('Participants joined:', e);
            })
            .listen('.Musonza\\Chat\\Eventing\\ParticipantsLeft', (e) => {
                console.log('Participants left:', e);
            })
            .listenForWhisper('typing', (e) => {
                handleTyping(e);
            });

        // Presence channel for online status
        presenceChannel = window.Echo.join(`chat.presence.${id}`)
            .here((users) => {
                onlineUsers.value = users;
            })
            .joining((user) => {
                if (!onlineUsers.value.find(u => u.id === user.id)) {
                    onlineUsers.value.push(user);
                }
            })
            .leaving((user) => {
                onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);
            });
    };

    const handleTyping = (event) => {
        if (!event.user) return;

        const existingIndex = typingUsers.value.findIndex(u => u.id === event.user.id);
        if (existingIndex < 0) {
            typingUsers.value.push(event.user);
        }

        // Remove typing indicator after 3 seconds
        setTimeout(() => {
            typingUsers.value = typingUsers.value.filter(u => u.id !== event.user.id);
        }, 3000);
    };

    const sendTypingIndicator = (user) => {
        if (channel) {
            channel.whisper('typing', { user });
        }
    };

    const unsubscribe = () => {
        if (currentConversationId && window.Echo) {
            // Leave both channels using Echo.leave()
            window.Echo.leave(`mc-chat-conversation.${currentConversationId}`);
            window.Echo.leave(`chat.presence.${currentConversationId}`);
        }
        channel = null;
        presenceChannel = null;
        currentConversationId = null;
        realtimeMessages.value = [];
        typingUsers.value = [];
    };

    watch(conversationId, (newId) => {
        if (newId) {
            subscribeToConversation(newId);
        } else {
            unsubscribe();
        }
    }, { immediate: true });

    onMounted(() => {
        if (conversationId.value) {
            subscribeToConversation(conversationId.value);
        }
    });

    onUnmounted(() => {
        unsubscribe();
    });

    return {
        realtimeMessages,
        onlineUsers,
        typingUsers,
        sendTypingIndicator,
    };
}
