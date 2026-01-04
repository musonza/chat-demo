<?php

use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::post('/conversations/{id}/join', [ConversationController::class, 'join']);
    Route::post('/conversations/{id}/leave', [ConversationController::class, 'leave']);
    Route::get('/conversations/{id}/participants', [ConversationController::class, 'participants']);

    Route::get('/conversations/{id}/messages', [MessageController::class, 'index']);
    Route::post('/conversations/{id}/messages', [MessageController::class, 'store']);
    Route::post('/conversations/{id}/read', [MessageController::class, 'markRead']);
    Route::delete('/conversations/{id}/messages/{messageId}', [MessageController::class, 'destroy']);
    Route::post('/conversations/{id}/messages/{messageId}/flag', [MessageController::class, 'toggleFlag']);

    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount']);
});
