<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\User;
use Chat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $alice = User::factory()->create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
        ]);

        $bob = User::factory()->create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        $charlie = User::factory()->create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@example.com',
            'password' => bcrypt('password'),
        ]);

        $diana = User::factory()->create([
            'name' => 'Diana Ross',
            'email' => 'diana@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create demo bots
        $supportBot = Bot::create([
            'name' => 'Support Bot',
            'type' => 'support',
            'description' => 'I can help answer your questions about the chat system.',
            'capabilities' => ['auto_reply', 'support'],
            'is_active' => true,
        ]);

        $welcomeBot = Bot::create([
            'name' => 'Welcome Bot',
            'type' => 'greeter',
            'description' => 'I welcome new users and help them get started.',
            'capabilities' => ['welcome_message'],
            'is_active' => true,
        ]);

        $notificationBot = Bot::create([
            'name' => 'Notification Bot',
            'type' => 'notification',
            'description' => 'I send important system notifications.',
            'capabilities' => ['notifications'],
            'is_active' => true,
        ]);

        // Create a direct message conversation between Alice and Bob
        $dmConversation = Chat::makeDirect()->createConversation([$alice, $bob]);

        Chat::message('Hey Bob! Have you tried the new chat demo?')
            ->from($alice)
            ->to($dmConversation)
            ->send();

        Chat::message('Hi Alice! Yes, it looks amazing! Love the real-time features.')
            ->from($bob)
            ->to($dmConversation)
            ->send();

        Chat::message('The message encryption is a nice touch too!')
            ->from($alice)
            ->to($dmConversation)
            ->send();

        // Create a group conversation
        $teamChat = Chat::createConversation([$alice, $bob, $charlie], [
            'name' => 'Team Chat',
            'description' => 'Our team discussion room',
        ]);

        Chat::message('Welcome to the team chat everyone!')
            ->from($alice)
            ->to($teamChat)
            ->send();

        Chat::message('Great to be here!')
            ->from($charlie)
            ->to($teamChat)
            ->send();

        Chat::message('Looking forward to collaborating with you all.')
            ->from($bob)
            ->to($teamChat)
            ->send();

        // Create a public conversation (discoverable)
        $publicLounge = Chat::createConversation([$alice, $supportBot], [
            'name' => 'Public Lounge',
            'description' => 'A public chat room for everyone. Feel free to join!',
        ]);
        $publicLounge->makePrivate(false);

        Chat::message('Welcome to the Public Lounge! This is a public conversation that anyone can discover and join.')
            ->from($supportBot)
            ->to($publicLounge)
            ->send();

        Chat::message('Feel free to chat about anything here!')
            ->from($alice)
            ->to($publicLounge)
            ->send();

        // Create a bot conversation
        $botChat = Chat::makeDirect()->createConversation([$alice, $welcomeBot]);

        Chat::message('Hello! I am the Welcome Bot. I am here to help you get started with the chat demo.')
            ->from($welcomeBot)
            ->to($botChat)
            ->send();

        Chat::message('This demo showcases features like real-time messaging, message encryption, and multi-participant support.')
            ->from($welcomeBot)
            ->to($botChat)
            ->send();

        // Create another public room
        $techTalk = Chat::createConversation([$bob, $charlie, $notificationBot], [
            'name' => 'Tech Talk',
            'description' => 'Discuss the latest in technology and programming',
        ]);
        $techTalk->makePrivate(false);

        Chat::message('Welcome to Tech Talk! Share your thoughts on the latest tech trends.')
            ->from($notificationBot)
            ->to($techTalk)
            ->send();

        Chat::message('Has anyone tried the new Laravel 12 features?')
            ->from($bob)
            ->to($techTalk)
            ->send();

        Chat::message('Yes! The improved Eloquent features are amazing.')
            ->from($charlie)
            ->to($techTalk)
            ->send();

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('');
        $this->command->info('Demo Users:');
        $this->command->info('  - alice@example.com (password: password)');
        $this->command->info('  - bob@example.com (password: password)');
        $this->command->info('  - charlie@example.com (password: password)');
        $this->command->info('  - diana@example.com (password: password)');
    }
}
