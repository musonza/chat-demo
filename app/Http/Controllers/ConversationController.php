<?php

namespace App\Http\Controllers;

use Chat;
use Illuminate\Http\Request;
use Musonza\Chat\Models\Conversation;

class ConversationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $conversations = Chat::conversations()->conversation->all();
        return response($conversations);
    }

    public function store()
    {
        $participants = [auth()->user()];
        $conversation = Chat::createConversation($participants);

        return response($conversation);
    }

    public function participants($conversationId)
    {
        $participants = Chat::conversations()->getById($conversationId)->users;

        return response($participants);
    }

    public function join(Conversation $conversation)
    {
        Chat::conversation($conversation)->addParticipants([auth()->user()]);
        return response('');
    }

    public function leaveConversation(Conversation $conversation)
    {
        Chat::conversation($conversation)->removeParticipants([auth()->user()]);
        return response('');
    }

    public function getMessages(Conversation $conversation)
    {
        $messages = Chat::conversation($conversation)->setParticipant(auth()->user())->getMessages();

        return response($messages);
    }

    public function deleteMessages(Conversation $conversation)
    {
        Chat::conversation($conversation)->setParticipant(auth()->user())->clear();
        return response('');
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $message = Chat::message($request->message)
                  ->from(auth()->user())
                  ->to($conversation)
                  ->send();

      return response($message);
    }
}
