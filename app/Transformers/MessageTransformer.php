<?php

namespace App\Transformers;

use Musonza\Chat\Transformers\Transformer;

class MessageTransformer extends Transformer
{

    public function transform($message)
    {
        return [
            'body' => $message->body,
            'conversation_id' => $message->conversation_id,
            'type' => $message->type,
            'read_at' => $message->read_at,
            'created_at' => $message->created_at,
            'direct_message' => $message->direct_message,
            'sender' => $message->sender,
        ];
    }
}
