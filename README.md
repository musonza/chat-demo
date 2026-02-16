# Chat Demo

A simple demo application showcasing the [musonza/chat](https://github.com/musonza/chat) Laravel package. This application demonstrates how to build a real-time chat system using Laravel, Vue.js, Inertia.js, and Laravel Reverb.

## Features

-   💬 Real-time messaging with Laravel Reverb
-   👥 User-to-user conversations
-   🤖 Bot conversations
-   🔐 Authentication with Laravel Breeze
-   📱 Responsive Vue.js frontend with Inertia.js
-   🔒 Message encryption support
-   ✨ Modern UI with Tailwind CSS

## Screenshots

<!-- Add screenshots here -->
<!-- Example:
![Chat Interface](./screenshots/chat-interface.png)
![Conversation List](./screenshots/conversations.png)
![Bot Chat](./screenshots/bot-chat.png)
-->

## 🚀 Live Demo

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/template/your-template-id)

> **Try it live**: [chat-demo.up.railway.app](https://chat-demo.up.railway.app) *(coming soon)*

## Requirements

-   PHP >= 8.2
-   Composer
-   Node.js >= 18.x and npm
-   SQLite (or MySQL/PostgreSQL)

## Installation

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd chat-demo
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install JavaScript dependencies**

    ```bash
    npm install
    ```

4. **Set up environment file**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Configure your database**

    Edit `.env` and set your database configuration. For SQLite (default):

    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=/absolute/path/to/database/database.sqlite
    ```

6. **Run migrations**

    ```bash
    php artisan migrate
    ```

7. **Build frontend assets**

    ```bash
    npm run build
    ```

    Or for development:

    ```bash
    npm run dev
    ```

8. **Start the development server**

    ```bash
    php artisan serve
    ```

    For full development setup (server, queue, logs, and Vite):

    ```bash
    composer run dev
    ```

## Configuration

### Laravel Reverb (Real-time)

Make sure to configure Laravel Reverb in your `.env` file:

```env
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

Start the Reverb server:

```bash
php artisan reverb:start
```

### Broadcasting

The application uses Laravel's broadcasting system. Make sure your `.env` has:

```env
BROADCAST_DRIVER=reverb
```

## Usage

1. **Register a new account** or login with existing credentials
2. **Start a conversation** by clicking "New Conversation" and selecting a user
3. **Chat with bots** by navigating to the Discover page
4. **Send messages** in real-time - messages appear instantly for all participants

## Tech Stack

-   **Backend**: Laravel 12
-   **Frontend**: Vue.js 3 + Inertia.js
-   **Real-time**: Laravel Reverb
-   **Styling**: Tailwind CSS
-   **Chat Package**: [musonza/chat](https://github.com/musonza/chat)

## Project Structure

```
chat-demo/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/          # API endpoints for conversations and messages
│   │   └── ChatController.php
│   └── Models/
│       └── Bot.php       # Bot model for chat bots
├── resources/
│   └── js/
│       ├── Components/Chat/    # Vue chat components
│       ├── Pages/Chat/         # Chat pages
│       └── Composables/        # Vue composables for messages and real-time
└── database/
    └── migrations/       # Database migrations including chat tables
```

## Development

### Running Tests

```bash
php artisan test
```

### Code Style

This project uses Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```

## Contributing

This is a demo application. If you'd like to contribute to the underlying chat package, please visit [musonza/chat](https://github.com/musonza/chat).

## License

This demo application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Deploy to Railway

### One-Click Deploy

Click the button above or follow these steps:

### Manual Deployment

1. **Create a Railway account** at [railway.app](https://railway.app)

2. **Create a new project** and add a PostgreSQL database

3. **Deploy from GitHub**
   - Connect your GitHub account
   - Select this repository
   - Railway will auto-detect the Laravel app

4. **Set environment variables** in Railway dashboard:

   ```env
   APP_NAME="Chat Demo"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-app.up.railway.app
   
   # Database (auto-filled if using Railway PostgreSQL)
   DB_CONNECTION=pgsql
   DB_HOST=${{Postgres.PGHOST}}
   DB_PORT=${{Postgres.PGPORT}}
   DB_DATABASE=${{Postgres.PGDATABASE}}
   DB_USERNAME=${{Postgres.PGUSER}}
   DB_PASSWORD=${{Postgres.PGPASSWORD}}
   
   # Generate with: php artisan key:generate --show
   APP_KEY=base64:your-generated-key
   
   # For real-time (Option A: Pusher - recommended for demo)
   BROADCAST_CONNECTION=pusher
   PUSHER_APP_ID=your-pusher-app-id
   PUSHER_APP_KEY=your-pusher-key
   PUSHER_APP_SECRET=your-pusher-secret
   PUSHER_APP_CLUSTER=us2
   
   VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
   VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
   ```

5. **For real-time WebSockets**, choose one option:

   **Option A: Pusher (Easiest)**
   - Sign up at [pusher.com](https://pusher.com) (free tier: 200k messages/day)
   - Create a Channels app and copy credentials
   - Update `.env` as shown above
   
   **Option B: Soketi on Railway**
   - Add Soketi as a separate Railway service
   - Configure it as a Pusher-compatible server
   
   **Option C: Run without real-time**
   - Set `BROADCAST_CONNECTION=log`
   - Messages will save but won't appear instantly (refresh to see new messages)

### Railway Template

To create a one-click deploy template:

1. Deploy the app manually first
2. Go to Railway dashboard → Project Settings → Generate Template
3. Replace the button URL in this README

## Related Projects

-   [musonza/chat](https://github.com/musonza/chat) - The Laravel chat package powering this demo
