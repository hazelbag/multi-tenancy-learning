# Work on Learning Multi Tenants in Laravel

This project is for myself to further learn the tenancy model and usage. Feel free to fork this repo and use it as you'd like
## Setup

``` bash
git clone https://github.com/hazelbag/multi-tenancy-learning.git

cp .env.example .env

composer install

touch database/database.sqlite

npm install

npm run dev

sail up -d
```

### env file

Set the DB_CONNECTION in your env file to sqlite and remove the remainder of the DB_ fields

### Process Followed

```text
composer require stancl/tenancy

php artisan tenancy:install

composer require laravel/jetstream

php artisan jetstream:install livewire --teams
```

### Migrations

Ensure that any new migrations are created under the tenants directory

### Final Steps to bring it all up

```text
sail bash

php artisan migrate

php artisan tenants:migrate
```

## Authors

- [@hazelbag](https://www.github.com/hazelbag)
