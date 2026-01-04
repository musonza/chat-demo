<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\User;
use Chat;
use Inertia\Inertia;
use Inertia\Response;
use Musonza\Chat\Models\Conversation;

class ChatController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();

        $conversations = Chat::conversations()
            ->setParticipant($user)
            ->get();

        return Inertia::render('Chat/Index', [
            'conversations' => $conversations,
            'currentUser' => $user->getParticipantDetails(),
            'users' => User::where('id', '!=', $user->id)->get(['id', 'name', 'email']),
            'bots' => Bot::where('is_active', true)->get(['id', 'name', 'type', 'avatar', 'description']),
        ]);
    }

    public function show(int $conversationId): Response
    {
        $user = auth()->user();
        $conversation = Chat::conversations()->getById($conversationId);

        $messages = Chat::conversation($conversation)
            ->setParticipant($user)
            ->getMessages();

        $participants = $conversation->getParticipants();

        $unreadCount = Chat::conversation($conversation)
            ->setParticipant($user)
            ->unreadCount();

        Chat::conversation($conversation)
            ->setParticipant($user)
            ->readAll();

        $allConversations = Chat::conversations()
            ->setParticipant($user)
            ->get();

        return Inertia::render('Chat/Show', [
            'conversation' => $conversation,
            'messages' => $messages,
            'participants' => $participants,
            'currentUser' => $user->getParticipantDetails(),
            'conversations' => $allConversations,
            'users' => User::where('id', '!=', $user->id)->get(['id', 'name', 'email']),
            'bots' => Bot::where('is_active', true)->get(['id', 'name', 'type', 'avatar', 'description']),
        ]);
    }

    public function discover(): Response
    {
        $user = auth()->user();

        $publicConversations = Chat::conversations()
            ->isPrivate(false)
            ->get();

        return Inertia::render('Chat/Discover', [
            'conversations' => $publicConversations,
            'currentUser' => $user->getParticipantDetails(),
        ]);
    }

    public function directMessage(User $targetUser): Response
    {
        $user = auth()->user();

        $conversation = Chat::conversations()
            ->between($user, $targetUser);

        if (!$conversation) {
            $conversation = Chat::makeDirect()
                ->createConversation([$user, $targetUser]);
        }

        return redirect()->route('chat.show', $conversation->id);
    }
}
