<template>
  <ul class="chat">
    <li class="left clearfix" v-for="(participant, index) in participants" :key="index">
      <div class="chat-body clearfix">
        <div class="header">
          <strong class="primary-font">ID: {{ participant.messageable_id }}</strong>
          <strong class="primary-font">Model: {{ participant.messageable_type }}</strong>
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
      axios.get(`/conversations/${conversationId}/participants`).then(response => {
        this.participants = response.data;
      });
    }
  },

  created() {
    this.getParticipants(this.conversation);
  }
};
</script>
