@extends('layouts.app')

@section('content')

@php
$conversationId = Request::query('conversation_id');
@endphp
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
          <chat-conversations></chat-conversations>
        </div>
        <div class="col-md-6">
          <h3>Conversation #{{ $conversationId }}</h3>
          <hr/>
          <div class="container chats">
              <div class="row">
                  <div class="col-md-12">
                      <div class="card card-default">
                          @if($conversationId)
                          <div class="card-header">
                            Messages
                          </div>
                          <div class="card-body">
                              <chat-messages :conversation="{{ $conversationId }}"></chat-messages>
                          </div>
                          <div class="card-footer">
                              <chat-form
                                      :conversation="{{ $conversationId }}"
                                      :user="{{ auth()->user() }}"
                              ></chat-form>
                          </div>
                          @endif
                      </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="col-md-3">
          <h3>Participants</h3>
          <hr/>
          @if($conversationId)
          <conversation-participants :conversation="{{ $conversationId }}"></conversation-participants>
          @endif
        </div>
    </div>
</div>
@endsection
