
## Installation
```
composer install
cp .env.example .env
php artisan key:generate
# add db credentials in .env file (see below)
php artisan migrate:fresh --seed
npm install
npm run dev
```

## .env required configuration
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=db_user
DB_PASSWORD=db_pass
```

## Test User

**User**: leprix@test.me

**Pass**: leprixpass
