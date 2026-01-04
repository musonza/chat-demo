<script setup>
import { computed } from 'vue';

const props = defineProps({
    participants: Array,
    onlineUsers: Array,
    currentUser: Object,
});

const isOnline = (participant) => {
    if (!props.onlineUsers) return false;
    return props.onlineUsers.some(
        u => u.id === participant.messageable_id && u.type === getType(participant)
    );
};

const getType = (participant) => {
    if (participant.messageable_type?.includes('Bot')) return 'bot';
    return 'user';
};

const getName = (participant) => {
    return participant.messageable?.name || 'Unknown';
};

const getInitials = (participant) => {
    const name = getName(participant);
    return name.substring(0, 2).toUpperCase();
};

const isCurrentUser = (participant) => {
    return participant.messageable_id === props.currentUser?.id &&
           getType(participant) === props.currentUser?.type;
};
</script>

<template>
    <div class="h-full flex flex-col">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Participants</h3>
            <p class="text-xs text-gray-500 mt-1">{{ participants?.length || 0 }} members</p>
        </div>

        <div class="flex-1 overflow-y-auto p-2">
            <div
                v-for="participant in participants"
                :key="`${participant.messageable_type}-${participant.messageable_id}`"
                class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors"
            >
                <div class="relative">
                    <div
                        :class="[
                            'w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-medium',
                            getType(participant) === 'bot' ? 'bg-purple-500' : 'bg-indigo-500'
                        ]"
                    >
                        {{ getInitials(participant) }}
                    </div>
                    <span
                        v-if="isOnline(participant)"
                        class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"
                    ></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ getName(participant) }}
                        <span v-if="isCurrentUser(participant)" class="text-gray-400">(you)</span>
                    </p>
                    <p class="text-xs text-gray-500 capitalize">
                        {{ getType(participant) }}
                        <span v-if="isOnline(participant)" class="text-green-600">• Online</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
