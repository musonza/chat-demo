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

## Related Projects

-   [musonza/chat](https://github.com/musonza/chat) - The Laravel chat package powering this demo
