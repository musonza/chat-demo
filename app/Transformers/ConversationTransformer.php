<?php

namespace App\Transformers;

use Musonza\Chat\Models\Conversation;
use Musonza\Chat\Transformers\Transformer;

class ConversationTransformer extends Transformer
{
    protected $defaultIncludes = ['participants'];

    public function transform($conversation)
    {
        return [
            'id' => $conversation->id,
            'private' => $conversation->private,
            'data' => $conversation->data,
            'created_at' => $conversation->created_at,
            'updated_at' => $conversation->updated_at,
        ];
    }

    protected function includeParticipants(Conversation $conversation)
    {
        return $this->collection($conversation->getParticipants(), new ParticipantTransformer());
    }
}
