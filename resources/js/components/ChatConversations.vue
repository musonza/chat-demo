<template>
    <div>
        <h3>Conversations</h3>
        <hr>
        <button class="btn btn-primary btn-sm" @click="createConversation()">New Conversation</button>
        <br>
        <br>
        <ul class="conversation">
            <li class="left clearfix" v-for="(convo, index) in conversations" :key="index">
                <div class="chat-body clearfix">
                    <div class="header">
                        <a href="#" @click="showConversation(convo.id)">
                            <strong class="primary-font">Conversation {{ convo.id }}</strong>
                        </a> |
                        <a
                            v-if="!isParticipant(convo.id)"
                            href="#"
                            class="text-success"
                            @click="joinConversation(convo.id)"
                        >
                            <strong>Join</strong>
                        </a>
                        <a
                            v-else
                            href="#"
                            class="text-danger"
                            title="leave conversation"
                            @click="leaveConversation(convo.id)"
                        >
                            <strong>Leave</strong>
                        </a>
                    </div>
                    <p></p>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data: () => ({
            conversation: null,
            conversations: []
        }),

        props: ["messages"],

        methods: {
            createConversation() {
                axios.post("/chat/conversations").then(response => {
                    location.reload();
                });
            },

            fetchConversations() {
                axios.get("/chat/conversations").then(response => {
                    this.conversations = response.data.data;
                });
            },

            showConversation(id) {
                window.location.href = "home?conversation_id=" + id;
            },

            isParticipant(id) {
                return window.conversations.indexOf(id) !== -1;
            },

            leaveConversation(id) {
                let participationId;
                const convo = this.conversations.find(c => c.id === id);
                participationId = (convo.participants.data.find(p => p.id == window.participant.id)).participation[0]['id'];

                axios.delete(`/chat/conversations/${id}/participants/${participationId}`).then(() => {
                    window.location.href = "home?conversation_id=" + id;
                });
            },

            joinConversation(id) {
                const payload = {
                    'participants': [
                        {
                            'id': window.participant.id,
                            'type': window.participant.type,
                        }
                    ]
                };

                axios.post(`/chat/conversations/${id}/participants`, payload).then(response => {
                    window.location.href = "home?conversation_id=" + id;
                });
            }
        },

        created() {
            this.fetchConversations();
        }
    };
</script>
