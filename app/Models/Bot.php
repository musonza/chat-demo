<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Musonza\Chat\Traits\Messageable;

class Bot extends Model
{
    use HasFactory, Messageable;

    protected $fillable = [
        'name',
        'type',
        'avatar',
        'description',
        'capabilities',
        'is_active',
    ];

    protected $casts = [
        'capabilities' => 'array',
        'is_active' => 'boolean',
    ];

    public function getParticipantDetails(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'type' => 'bot',
            'bot_type' => $this->type,
            'description' => $this->description,
        ];
    }
}
