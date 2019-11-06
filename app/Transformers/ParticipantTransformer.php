<?php

namespace App\Transformers;

use Musonza\Chat\Transformers\Transformer;

class ParticipantTransformer extends Transformer
{
    public function transform($item)
    {
        return $item->toArray();
    }
}
