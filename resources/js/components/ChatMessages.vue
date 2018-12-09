<template>
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
      alert(this.conversation);
    }
  },

  created() {
    this.fetchMessages();
  }
};
</script>