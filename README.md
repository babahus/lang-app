# Language App

## Project Installation

### Requirements
- Docker
- GIT CLI
- Ngrok

#### Using Docker Commands

1. Clone The Project
2. Run `cp .env.example .env`
3. Replace ENV Values in **.env** file
4. Run `docker compose -f docker-compose.yml pull`
5. Run `docker compose -f docker-compose.yml build --pull`
6. Run `docker compose -f docker-compose.yml up -d`
7. Install Composer Deps. Run `docker compose -f docker-compose.yml run --rm lang_app_php composer install`
8. Set APP_KEY. Run `docker compose -f docker-compose.yml run --rm lang_app_php php artisan key:generate`
9. Cache Project Config `docker compose -f docker-compose.yml run --rm lang_app_php php artisan config:cache`
10. Run Migrations. `docker compose -f docker-compose.yml run --rm lang_app_php php artisan migrate`
11. Run Seeders. `docker compose -f docker-compose.yml run --rm lang_app_php php artisan db:seed`
12. (Optional) Run Ngrok `ngrok http 8803` and replace env value ASSEMBLYAI_WEBHOOK_URL to your value

## Environment Variables

- `NGINX_HOST_PORT` - Forwarded API Port.

- `DB_HOST_PATH` - Folders For Storing Docker's DB Files. Default: `database`;
- `DB_ROOT_PASSWORD` - Local Database Password;
- `DB_HOST_PORT` - Forwarded Host's Local DB Port;

## Local Database 

- User: `root`
- Database: `lang_app`
- Password: Value Of `DB_ROOT_PASSWORD` ENV Variable
