<template>
  <div>
    <button class="btn btn-danger btn-sm float-right" @click="deleteMessages()">Delete Messages</button>
    <br />
    <br />
    <ul class="chat">
      <li
        :class="[currentUser.id == message.sender.id ? 'right' : 'left']"
        class="clearfix mb-1"
        v-for="(message, index) in messages.data"
        :key="index"
      >
        <div class="chat-body clearfix">
          <div v-if="currentUser.id != message.sender.id" class="header">
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
  computed: {
    currentUser() {
      return window.participant;
    }
  },
  methods: {
    fetchMessages() {
      axios
        .get(
          `/chat/conversations/${this.conversation}/messages?participant_id=${window.participant.id}&participant_type=${window.participant.type}`
        )
        .then(response => {
          this.messages = response.data;
        });
    },

    deleteMessages() {
      axios
        .delete(
          `/chat/conversations/${this.conversation}/messages?participant_id=${window.participant.id}&participant_type=${window.participant.type}`
        )
        .then(response => {
          this.messages = response.data;
        });
    },

    enablePusher() {
      let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER
      });

      let channel = pusher.subscribe(
        `mc-chat-conversation.${this.conversation}`
      );
      channel.bind("Musonza\\Chat\\Eventing\\MessageWasSent", data => {
        this.messages.data.push(data.message);
      });
    }
  },

  created() {
    this.fetchMessages();
    //this.enablePusher();
  }
};
</script>
<style lang="scss" scoped>
ul.chat {
  list-style: none;
  padding: 0;
  li {
    .chat-body {
      border-radius: 0.6rem;
      width: fit-content;
      padding: 4px 10px;
      .header {
        font-size: .85rem;
      }
      p {
        margin: 0;
      }
    }
    &.left {
      .chat-body {
        float: left;
        background: rgb(177, 225, 235);
      }
    }
    &.right {
      .chat-body {
        float: right;
        background: rgb(170, 167, 167);
      }
    }
  }
}
</style>