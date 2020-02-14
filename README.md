# chat-demo

This is a simple demo application for https://github.com/musonza/chat/

<p align="left"><img src="chat-screen1.png" alt="chat" width=""></p>

1. Clone this repo
2. `composer install`
3. `cp .env.example .env`
4. Create database and modify .env
5. `php artisan migrate`
6. `php artisan key:generate`
7. `npm install && npm run dev`
8. `php artisan serve`
9. Create 2 user accounts in different browsers and start chatting

To use Pusher

You need to comment this out https://github.com/musonza/chat-demo/blob/5985fea7b19a4c5f20f86fbc8502798e9ae9508a/resources/js/components/ChatForm.vue#L55
Remove this comment
https://github.com/musonza/chat-demo/blob/5985fea7b19a4c5f20f86fbc8502798e9ae9508a/resources/js/components/ChatMessages.vue#L60
