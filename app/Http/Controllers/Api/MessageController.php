<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Chat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, int $conversationId): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
            'type' => 'sometimes|string|in:text,image,file,link',
            'data' => 'sometimes|array',
        ]);

        $user = auth()->user();
        $conversation = Chat::conversations()->getById($conversationId);

        $messageBuilder = Chat::message($validated['body'])
            ->type($validated['type'] ?? 'text')
            ->from($user)
            ->to($conversation);

        if (isset($validated['data'])) {
            $messageBuilder->data($validated['data']);
        }

        $message = $messageBuilder->send();

        return response()->json([
            'message' => $message,
            'sender' => $message->sender,
        ], 201);
    }

    public function markRead(int $conversationId): JsonResponse
    {
        $user = auth()->user();
        $conversation = Chat::conversations()->getById($conversationId);

        Chat::conversation($conversation)
            ->setParticipant($user)
            ->readAll();

        return response()->json([
            'message' => 'Messages marked as read',
        ]);
    }

    public function index(int $conversationId): JsonResponse
    {
        $user = auth()->user();
        $conversation = Chat::conversations()->getById($conversationId);

        $messages = Chat::conversation($conversation)
            ->setParticipant($user)
            ->getMessages();

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function destroy(int $conversationId, int $messageId): JsonResponse
    {
        $user = auth()->user();
        $message = Chat::messages()->getById($messageId);

        Chat::message($message)
            ->setParticipant($user)
            ->delete();

        return response()->json([
            'message' => 'Message deleted',
        ]);
    }

    public function toggleFlag(int $conversationId, int $messageId): JsonResponse
    {
        $user = auth()->user();
        $message = Chat::messages()->getById($messageId);

        Chat::message($message)
            ->setParticipant($user)
            ->toggleFlag();

        $isFlagged = Chat::message($message)
            ->setParticipant($user)
            ->flagged();

        return response()->json([
            'flagged' => $isFlagged,
        ]);
    }

    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();

        $count = Chat::messages()
            ->setParticipant($user)
            ->unreadCount();

        return response()->json([
            'unread_count' => $count,
        ]);
    }
}
