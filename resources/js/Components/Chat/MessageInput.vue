<script setup>
import { ref, computed } from 'vue';
import { debounce } from 'lodash-es';

const props = defineProps({
    isSending: Boolean,
});

const emit = defineEmits(['send', 'typing']);

const message = ref('');
const textareaRef = ref(null);

const canSend = computed(() => message.value.trim().length > 0 && !props.isSending);

const handleSubmit = () => {
    if (!canSend.value) return;

    emit('send', message.value.trim());
    message.value = '';
    textareaRef.value?.focus();
};

const handleKeydown = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        handleSubmit();
    }
};

const handleInput = debounce(() => {
    emit('typing');
}, 500);
</script>

<template>
    <div class="border-t border-gray-200 p-4 bg-white">
        <form @submit.prevent="handleSubmit" class="flex items-end gap-3">
            <div class="flex-1">
                <textarea
                    ref="textareaRef"
                    v-model="message"
                    @keydown="handleKeydown"
                    @input="handleInput"
                    placeholder="Type a message..."
                    rows="1"
                    class="w-full resize-none rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4"
                    :disabled="isSending"
                ></textarea>
            </div>
            <button
                type="submit"
                :disabled="!canSend"
                class="p-3 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                <svg v-if="isSending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>
</template>
