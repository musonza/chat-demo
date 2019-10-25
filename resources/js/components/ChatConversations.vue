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
      axios.post("/conversations").then(response => {
        location.reload();
      });
    },

    fetchConversations() {
      axios.get("/conversations").then(response => {
        this.conversations = response.data;
      });
    },

    showConversation(id) {
      window.location.href = "home?conversation_id=" + id;
    },

    isParticipant(id) {
      return window.conversations.indexOf(id) !== -1;
    },

    leaveConversation(id) {
      axios.delete(`/conversations/${id}/participants`).then(response => {
        window.location.href = "home?conversation_id=" + id;
      });
    },

    joinConversation(id) {
      axios.post(`/conversations/${id}/participants`).then(response => {
        window.location.href = "home?conversation_id=" + id;
      });
    }
  },

  created() {
    this.fetchConversations();
  }
};
</script>
