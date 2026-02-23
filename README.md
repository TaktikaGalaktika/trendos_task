# 🚀 Trendos Notification API
A clean, production-ready implementation of the Notification Center API using Symfony 7 and PHP 8.2.

## 🏗️ Key Highlights

- Performance: Optimized UserRepository with a single LEFT JOIN to prevent N+1 query issues.

- Service Layer: Business logic is isolated in NotificationService for better maintainability.

- i18n: Multi-language support (EN/ES) powered by Symfony Translation, determined by user data.

- Strict Typing: Full use of strict_types=1, readonly properties, and modern PHP 8.2 standards.

- Automated Setup: Database schema and fixtures are auto-imported via Docker on startup.

## 🛠️ Setup

1. docker compose up -d
2. docker compose exec php composer install
3. docker compose exec php bin/console cache:clear
4. The API will be live at: http://localhost:8080/notifications?user_id=1

## 🧪 Tech Stack
- PHP 8.2 (Strict Mode)
- Symfony 7 (Skeleton + ORM Pack + Translation)
- MySQL 8.0
- Nginx
