<template>
    <div>
        <div class="input-group" v-if="isParticipant()">
            <input
                id="btn-input"
                type="text"
                name="message"
                class="form-control input-sm"
                placeholder="Type your message here..."
                v-model="newMessage"
                @keyup.enter="sendMessage"
            >
            &nbsp;&nbsp;
            <span class="input-group-btn">
        <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">Send</button>
      </span>
        </div>
        <div v-else>
            <button class="btn btn-success btn-sm" @click="joinConversation()">Join Conversation</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ["user", "conversation"],

        data() {
            return {
                newMessage: ""
            };
        },

        methods: {
            isParticipant() {
                return window.conversations.indexOf(this.conversation) !== -1;
            },

            joinConversation() {
                axios.post(`/chat/conversations/${this.conversation}/participants`).then(response => {
                    location.reload();
                });
            },

            sendMessage() {
                axios.post(`/chat/conversations/${this.conversation}/messages`, {
                    message: {
                        body: this.newMessage,
                    },
                    participant_id: window.participant.id,
                    participant_type: window.participant.type,
                })
                    .then(() => {
                        this.newMessage = "";
                        location.reload(); // comment this out if you are broadcasting
                    });
            }
        }
    };
</script>
