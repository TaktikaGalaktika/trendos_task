# Trendos Backend Notification API

This is a Symfony-based API for the Notification Center task.

## Setup Instructions
1. Clone the repository.
2. Ensure Docker Desktop is running.
3. Run `docker compose up -d` to build and start the containers.
4. Import the database dump:
   `Get-Content dump.sql | docker compose exec -T database mysql -uroot -proot trendos_db`
5. The API is accessible at `http://localhost:8080/notifications?user_id=1`.

## Tech Stack
- PHP 8.2 (Symfony Skeleton)
- MySQL 8.0
- Nginx
- Adminer (Database UI at localhost:8081)
