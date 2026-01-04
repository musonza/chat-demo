<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\User;
use Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Musonza\Chat\Models\Conversation;

class ConversationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*.id' => 'required|integer',
            'participant_ids.*.type' => 'required|in:user,bot',
            'name' => 'nullable|string|max:255',
            'is_private' => 'boolean',
            'is_direct' => 'boolean',
        ]);

        $user = auth()->user();
        $participants = [$user];

        foreach ($validated['participant_ids'] as $participant) {
            if ($participant['type'] === 'user') {
                $participants[] = User::findOrFail($participant['id']);
            } else {
                $participants[] = Bot::findOrFail($participant['id']);
            }
        }

        $isDirect = $validated['is_direct'] ?? false;
        $isPrivate = $validated['is_private'] ?? true;

        if ($isDirect && count($participants) === 2) {
            $existing = Chat::conversations()->between($participants[0], $participants[1]);
            if ($existing) {
                return response()->json([
                    'conversation' => $existing,
                    'message' => 'Existing conversation found',
                ]);
            }

            $conversation = Chat::makeDirect()->createConversation($participants);
        } else {
            $conversation = Chat::createConversation($participants, [
                'name' => $validated['name'] ?? null,
            ]);

            if (!$isPrivate) {
                $conversation->makePrivate(false);
            }
        }

        return response()->json([
            'conversation' => $conversation,
            'message' => 'Conversation created successfully',
        ], 201);
    }

    public function join(int $conversationId): JsonResponse
    {
        $user = auth()->user();
        $conversation = Conversation::findOrFail($conversationId);

        if ($conversation->private) {
            return response()->json([
                'message' => 'Cannot join a private conversation',
            ], 403);
        }

        // Check if user is already a participant
        $isParticipant = $conversation->participants()
            ->where('messageable_id', $user->id)
            ->where('messageable_type', $user->getMorphClass())
            ->exists();

        if ($isParticipant) {
            return response()->json([
                'message' => 'Already a participant',
                'conversation' => $conversation,
            ]);
        }

        $conversation->addParticipants([$user]);

        return response()->json([
            'message' => 'Joined conversation successfully',
            'conversation' => $conversation,
        ]);
    }

    public function leave(int $conversationId): JsonResponse
    {
        $user = auth()->user();
        $conversation = Conversation::findOrFail($conversationId);

        $conversation->removeParticipant($user);

        return response()->json([
            'message' => 'Left conversation successfully',
        ]);
    }

    public function participants(int $conversationId): JsonResponse
    {
        $conversation = Chat::conversations()->getById($conversationId);
        $participants = $conversation->getParticipants();

        return response()->json([
            'participants' => $participants,
        ]);
    }
}
