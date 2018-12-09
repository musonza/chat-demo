<template>
  <div>
    <button class="btn btn-danger btn-sm float-right" @click="deleteMessages()">Delete Messages</button>
    <br>
    <br>
    <ul class="chat">
      <li class="left clearfix" v-for="(message, index) in messages.data" :key="index">
        <div class="chat-body clearfix">
          <div class="header">
            <strong class="primary-font">{{ message.sender.name }}</strong>
          </div>
          <p>{{ message.body }}</p>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  props: ["conversation"],

  data: () => ({
    messages: []
  }),

  methods: {
    fetchMessages() {
      axios
        .get(`/conversations/${this.conversation}/messages`)
        .then(response => {
          this.messages = response.data;
        });
    },

    deleteMessages() {
      axios
        .delete(`/conversations/${this.conversation}/messages`)
        .then(response => {
          this.messages = response.data;
        });
    }
  },

  created() {
    this.fetchMessages();
  }
};
</script>