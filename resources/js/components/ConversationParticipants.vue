<template>
  <ul class="chat">
    <li class="left clearfix" v-for="(user, index) in participants" :key="index">
      <div class="chat-body clearfix">
        <div class="header">
          <strong class="primary-font">{{ user.name }}</strong>
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
      axios.get(`/conversations/${conversationId}/users`).then(response => {
        this.participants = response.data;
      });
    }
  },

  created() {
    this.getParticipants(this.conversation);
  }
};
</script>