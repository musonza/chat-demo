<template>
    <ul class="chat">
        <li class="left clearfix" v-for="(participant, index) in participants" :key="index">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">{{ participant.name }}</strong><br/>
                    <span>{{ participant.participation[0].messageable_type}}</span>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ["conversation"],

        data: () => ({
            participants: []
        }),

        methods: {
            getParticipants(conversationId) {
                axios.get(`/chat/conversations/${conversationId}/participants`).then(response => {
                    this.participants = response.data;
                });
            }
        },

        created() {
            this.getParticipants(this.conversation);
        }
    };
</script>
