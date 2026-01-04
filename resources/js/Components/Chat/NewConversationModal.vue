<script setup>
import { ref, computed, watch } from 'vue';
import Modal from '@/Components/Modal.vue';

// Use the configured axios instance from bootstrap.js (has withCredentials: true)
const axios = window.axios;

const props = defineProps({
    show: Boolean,
    users: Array,
    bots: Array,
});

const emit = defineEmits(['close', 'created']);

const conversationType = ref('direct');
const selectedParticipants = ref([]);
const conversationName = ref('');
const isPublic = ref(false);
const isCreating = ref(false);
const error = ref('');

const allParticipants = computed(() => {
    const participants = [];

    if (props.users) {
        participants.push(...props.users.map(u => ({
            id: u.id,
            name: u.name,
            type: 'user',
            label: u.email,
        })));
    }

    if (props.bots) {
        participants.push(...props.bots.map(b => ({
            id: b.id,
            name: b.name,
            type: 'bot',
            label: b.type,
            description: b.description,
        })));
    }

    return participants;
});

const canCreate = computed(() => {
    if (conversationType.value === 'direct') {
        return selectedParticipants.value.length === 1;
    }
    return selectedParticipants.value.length >= 1;
});

const toggleParticipant = (participant) => {
    const index = selectedParticipants.value.findIndex(
        p => p.id === participant.id && p.type === participant.type
    );

    if (index >= 0) {
        selectedParticipants.value.splice(index, 1);
    } else {
        if (conversationType.value === 'direct') {
            selectedParticipants.value = [participant];
        } else {
            selectedParticipants.value.push(participant);
        }
    }
};

const isSelected = (participant) => {
    return selectedParticipants.value.some(
        p => p.id === participant.id && p.type === participant.type
    );
};

const createConversation = async () => {
    if (!canCreate.value) return;

    isCreating.value = true;
    error.value = '';

    try {
        const response = await axios.post('/api/conversations', {
            participant_ids: selectedParticipants.value.map(p => ({
                id: p.id,
                type: p.type,
            })),
            name: conversationType.value === 'group' ? conversationName.value : null,
            is_private: !isPublic.value,
            is_direct: conversationType.value === 'direct',
        });

        emit('created', response.data.conversation);
        resetForm();
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to create conversation';
    } finally {
        isCreating.value = false;
    }
};

const resetForm = () => {
    conversationType.value = 'direct';
    selectedParticipants.value = [];
    conversationName.value = '';
    isPublic.value = false;
    error.value = '';
};

watch(() => props.show, (show) => {
    if (!show) {
        resetForm();
    }
});

watch(conversationType, () => {
    selectedParticipants.value = [];
});
</script>

<template>
    <Modal :show="show" @close="emit('close')" max-width="lg">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">New Conversation</h2>

            <!-- Conversation Type -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input
                            type="radio"
                            v-model="conversationType"
                            value="direct"
                            class="text-indigo-600 focus:ring-indigo-500"
                        />
                        <span class="ml-2 text-sm text-gray-700">Direct Message</span>
                    </label>
                    <label class="flex items-center">
                        <input
                            type="radio"
                            v-model="conversationType"
                            value="group"
                            class="text-indigo-600 focus:ring-indigo-500"
                        />
                        <span class="ml-2 text-sm text-gray-700">Group Chat</span>
                    </label>
                </div>
            </div>

            <!-- Group Name (for group chats) -->
            <div v-if="conversationType === 'group'" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Group Name</label>
                <input
                    type="text"
                    v-model="conversationName"
                    placeholder="Enter group name..."
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <!-- Public Toggle (for group chats) -->
            <div v-if="conversationType === 'group'" class="mb-4">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="isPublic"
                        class="rounded text-indigo-600 focus:ring-indigo-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">Make this conversation public (discoverable)</span>
                </label>
            </div>

            <!-- Participant Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ conversationType === 'direct' ? 'Select participant' : 'Select participants' }}
                </label>
                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg divide-y divide-gray-100">
                    <div
                        v-for="participant in allParticipants"
                        :key="`${participant.type}-${participant.id}`"
                        @click="toggleParticipant(participant)"
                        :class="[
                            'flex items-center gap-3 p-3 cursor-pointer transition-colors',
                            isSelected(participant) ? 'bg-indigo-50' : 'hover:bg-gray-50'
                        ]"
                    >
                        <div :class="[
                            'w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-medium',
                            participant.type === 'bot' ? 'bg-purple-500' : 'bg-indigo-500'
                        ]">
                            {{ participant.name.substring(0, 2).toUpperCase() }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ participant.name }}
                                <span v-if="participant.type === 'bot'" class="ml-1 text-xs text-purple-600">(Bot)</span>
                            </p>
                            <p class="text-xs text-gray-500">{{ participant.label }}</p>
                        </div>
                        <div v-if="isSelected(participant)" class="text-indigo-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-4 p-3 bg-red-50 text-red-700 text-sm rounded-lg">
                {{ error }}
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <button
                    @click="emit('close')"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors"
                >
                    Cancel
                </button>
                <button
                    @click="createConversation"
                    :disabled="!canCreate || isCreating"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <span v-if="isCreating" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating...
                    </span>
                    <span v-else>Create Conversation</span>
                </button>
            </div>
        </div>
    </Modal>
</template>
