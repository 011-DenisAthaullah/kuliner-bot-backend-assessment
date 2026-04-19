# 🍜 Kuliner Bot API

Backend API sistem kuliner berbasis Laravel 12 dengan JWT Authentication, Google Places API, Telegram Bot, Logging Middleware, Repository Pattern, dan Automated Testing.

---

## 🚀 Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL / SQLite (testing)
- JWT Auth (tymon/jwt-auth)
- Telegram Bot API
- Google Places API
- PHPUnit (Feature Testing)
- Postman Collection
- CI/CD GitHub Actions

---

## 📌 Fitur

### 🔐 Authentication (JWT)
- Register user
- Login user
- Token JWT authentication
- Password hashing

---

### 🍽️ Restaurant Module
- Search restaurant
- CRUD restaurant
- Integrasi Google Places API
- Repository Pattern

---

### ⭐ Review System
- User review restaurant
- Rating system
- Relasi user ↔ restaurant

---

### 🤖 Telegram Bot
- Webhook handling
- Message text response
- Location-based search
- Integrasi API restaurant

---

### 🧾 Logging Middleware
- Log request & response
- Simpan endpoint, method, user_id, IP
- Debugging API

---

### 🧪 Automated Testing
- Feature test Auth
- Feature test Restaurant
- Feature test Telegram webhook
- SQLite in-memory testing

---

## ⚙️ Installation

### 1. Clone Project
```bash
git clone https://github.com/username/kuliner-bot.git
cd kuliner-bot
2. Install Dependency
composer install
3. Setup Environment
cp .env.example .env
php artisan key:generate
4. Setup Database
MySQL

Edit .env:

DB_CONNECTION=mysql
DB_DATABASE=kuliner_bot
DB_USERNAME=root
DB_PASSWORD=
SQLite (testing)
touch database/database.sqlite

Lalu .env:

DB_CONNECTION=sqlite
5. Run Migration
php artisan migrate
6. Run Server
php artisan serve
🔐 Environment Variables

Tambahkan di .env

APP_NAME=KulinerBot
APP_URL=http://localhost:8000

JWT_SECRET=your_jwt_secret

TELEGRAM_BOT_TOKEN=your_telegram_token
GOOGLE_PLACES_API_KEY=your_google_api_key

Generate JWT secret:

php artisan jwt:secret
📡 API ENDPOINTS
🔐 AUTH
Register
POST /api/auth/register

Body:

{
  "name": "Denis",
  "email": "denis@test.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response:

{
  "user": {},
  "token": "jwt_token_here"
}
Login
POST /api/auth/login

Body:

{
  "email": "denis@test.com",
  "password": "password123"
}

Response:

{
  "token": "jwt_token_here"
}
🍽️ RESTAURANT
Search Restaurant
GET /api/restaurants/search?q=jakarta

Headers:

Authorization: Bearer {token}

Response:

{
  "data": []
}
⭐ REVIEW
Add Review
POST /api/restaurants/{id}/review

Body:

{
  "rating": 5,
  "comment": "Enak banget"
}
🤖 TELEGRAM BOT
Webhook
POST /api/telegram/webhook

Supports:

text message
location message
🧪 RUN TESTING
php artisan test

Expected:

PASS Tests\Feature\AuthTest
PASS Tests\Feature\RestaurantTest
PASS Tests\Feature\TelegramTest
🤖 TELEGRAM SETUP
1. Jalankan ngrok
ngrok http 8000
2. Set webhook
https://api.telegram.org/bot<TOKEN>/setWebhook?url=<NGROK_URL>/api/telegram/webhook