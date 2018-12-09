<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use Illuminate\Http\Request;
use Chat;
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
        $participants = [auth()->user()->id];
        $conversation = Chat::createConversation($participants);

        return response($conversation);
    }

    public function participants($conversationId)
    {
        $participants = Chat::conversations()->getById($conversationId)->users;

        return response($participants);
    }

    public function join(Request $request, Conversation $conversation)
    {
        Chat::conversation($conversation)->addParticipants(auth()->user());
        return response('');
    }

    public function leaveConversation(Request $request, Conversation $conversation)
    {
        Chat::conversation($conversation)->removeParticipants(auth()->user());
        return response('');
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $messages = Chat::conversation($conversation)->for(auth()->user())->getMessages();

        return response($messages);
    }

    public function deleteMessages(Conversation $conversation)
    {
        Chat::conversation($conversation)->for(auth()->user())->clear();
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