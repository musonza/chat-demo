<template>
  <div class="card card-default">
    <div class="card-header">Participants</div>
    <div class="card-body">
      <ul class="chat">
        <li class="left clearfix border-bottom py-1" v-for="(participant, index) in participants" :key="index">
          <div class="chat-body clearfix">
            <div class="d-flex align-items-center header">
              <div class="primary-font participant-name">{{ participant.name }}</div>
              
              <span class="participant-type pl-1"> ({{ participant.participation[0].messageable_type}})</span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ["conversation"],

  data: () => ({
    participants: []
  }),

  methods: {
    getParticipants(conversationId) {
      axios
        .get(`/chat/conversations/${conversationId}/participants`)
        .then(response => {
          this.participants = response.data;
        });
    }
  },

  created() {
    this.getParticipants(this.conversation);
  }
};
</script>
<style lang="scss" scoped>
ul.chat {
    padding: 0;
    list-style: none;
}
.participant-name {
    font-size: 1rem;
}

.participant-type {
    font-size: .8rem;
    color: #565151;
}
</style>