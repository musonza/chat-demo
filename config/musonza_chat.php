<?php

return [
    'user_model'            => 'App\User',

    /*
     * This will allow you to broadcast an event when a message is sent
     * Example:
     * Channel: private-mc-chat-conversation.2,
     * Event: Musonza\Chat\Eventing\MessageWasSent
     */
    'broadcasts'            => true,

    'sent_message_event' => 'Musonza\Chat\Eventing\MessageWasSent',
];
