<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Musonza\Chat\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('mc-chat-conversation.{conversationId}', function (User $user, int $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    return $conversation->participants()
        ->where('messageable_id', $user->id)
        ->where('messageable_type', $user->getMorphClass())
        ->exists();
});

Broadcast::channel('chat.presence.{conversationId}', function (User $user, int $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    $isParticipant = $conversation->participants()
        ->where('messageable_id', $user->id)
        ->where('messageable_type', $user->getMorphClass())
        ->exists();

    if ($isParticipant) {
        return $user->getParticipantDetails();
    }

    return false;
});
