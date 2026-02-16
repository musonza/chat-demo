<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Chat Demo - Modern Laravel Chat" />

    <div class="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
        <nav class="absolute top-0 right-0 p-6">
            <div class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth?.user"
                    :href="route('chat.index')"
                    class="px-4 py-2 text-white/90 hover:text-white transition-colors"
                >
                    Open Chat
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="px-4 py-2 text-white/90 hover:text-white transition-colors"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-4 py-2 bg-white text-indigo-600 rounded-lg font-medium hover:bg-white/90 transition-colors"
                    >
                        Register
                    </Link>
                </template>
            </div>
        </nav>

        <main class="flex flex-col items-center justify-center min-h-screen px-4">
            <div class="text-center max-w-3xl">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">
                        Chat Demo
                    </h1>
                    <p class="text-xl text-white/80 mb-8">
                        A modern, real-time chat application built with Laravel, Vue 3, and Inertia.js
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-left">
                        <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Message Encryption</h3>
                        <p class="text-white/70 text-sm">AES-256 encryption for all messages, ensuring your conversations stay private.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-left">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Real-time Updates</h3>
                        <p class="text-white/70 text-sm">Powered by Laravel Reverb WebSockets for instant message delivery.</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-left">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Multi-Participant</h3>
                        <p class="text-white/70 text-sm">Support for users and bots as participants in conversations.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <Link
                        v-if="$page.props.auth?.user"
                        :href="route('chat.index')"
                        class="px-8 py-3 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-white/90 transition-colors shadow-lg"
                    >
                        Open Chat
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-8 py-3 bg-white text-indigo-600 rounded-xl font-semibold hover:bg-white/90 transition-colors shadow-lg"
                        >
                            Get Started
                        </Link>
                        <a
                            href="https://github.com/musonza/chat"
                            target="_blank"
                            class="px-8 py-3 bg-white/10 text-white rounded-xl font-semibold hover:bg-white/20 transition-colors backdrop-blur-sm"
                        >
                            View on GitHub
                        </a>
                    </template>
                </div>

                <div class="mt-12 bg-white/10 backdrop-blur-sm rounded-xl p-6 max-w-md mx-auto">
                    <p class="text-white font-medium mb-4">🚀 Quick Demo Login</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a
                            :href="route('login') + '?email=alice@example.com'"
                            class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition-colors"
                        >
                            Alice
                        </a>
                        <a
                            :href="route('login') + '?email=bob@example.com'"
                            class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition-colors"
                        >
                            Bob
                        </a>
                        <a
                            :href="route('login') + '?email=charlie@example.com'"
                            class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition-colors"
                        >
                            Charlie
                        </a>
                    </div>
                    <p class="text-white/70 text-xs mt-3">Password: <code class="bg-white/20 px-1.5 py-0.5 rounded">password</code></p>
                </div>
            </div>
        </main>

        <footer class="absolute bottom-0 w-full p-6 text-center text-white/50 text-sm">
            Powered by musonza/chat - A complete chat solution for Laravel
        </footer>
    </div>
</template>
