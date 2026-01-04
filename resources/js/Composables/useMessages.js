import { ref } from 'vue';

// Use the configured axios instance from bootstrap.js (has withCredentials: true)
const axios = window.axios;

export function useMessages(conversationId) {
    const isSending = ref(false);
    const error = ref(null);

    const sendMessage = async (body, type = 'text') => {
        if (!conversationId.value) {
            error.value = 'No conversation selected';
            return null;
        }

        isSending.value = true;
        error.value = null;

        try {
            const response = await axios.post(`/api/conversations/${conversationId.value}/messages`, {
                body,
                type,
            });
            return response.data.message;
        } catch (e) {
            error.value = e.response?.data?.message || 'Failed to send message';
            throw e;
        } finally {
            isSending.value = false;
        }
    };

    const markAsRead = async () => {
        if (!conversationId.value) return;

        try {
            await axios.post(`/api/conversations/${conversationId.value}/read`);
        } catch (e) {
            console.error('Failed to mark as read:', e);
        }
    };

    const deleteMessage = async (messageId) => {
        if (!conversationId.value) return;

        try {
            await axios.delete(`/api/conversations/${conversationId.value}/messages/${messageId}`);
            return true;
        } catch (e) {
            error.value = e.response?.data?.message || 'Failed to delete message';
            return false;
        }
    };

    const toggleFlag = async (messageId) => {
        if (!conversationId.value) return;

        try {
            const response = await axios.post(`/api/conversations/${conversationId.value}/messages/${messageId}/flag`);
            return response.data.flagged;
        } catch (e) {
            error.value = e.response?.data?.message || 'Failed to flag message';
            return null;
        }
    };

    return {
        isSending,
        error,
        sendMessage,
        markAsRead,
        deleteMessage,
        toggleFlag,
    };
}
