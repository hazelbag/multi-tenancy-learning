# Work on Learning Multi Tenants in Laravel

This project is for myself to further learn the tenancy model and usage. Feel free to fork this repo and use it as you'd like
## Setup

``` bash
git clone https://github.com/hazelbag/multi-tenancy-learning.git

cp .env.example .env

composer install

npm install

npm run dev

sail up -d
```

### Process Followed

```text
composer require stancl/tenancy

php artisan tenancy:install

composer require laravel/jetstream

php artisan jetstream:install livewire --teams (if teams is needed)
```

### Migrations

Ensure that any new migrations are created under the tenants directory

### Final Steps to bring it all up

```text
sail bash

php artisan migrate

php artisan tenants:migrate
```

### When using sail

```text
Set the DB_USERNAME=sail when bringing it all up.
Once it is up and running set the DB_USERNAME to root and run `sail artisan config:clear` and create a tenant.
```

### Reset a Tenant Users Password

Be sure to have the project running beforehand - `sail up -d`

Create a custom command and name it anything you like - `php artisan tenant:reset-password {tenant} {email}`

In your command add the following to ensure you switch/initialize the tenant connection

```text
$email = $this->argument('email'); - get the email from the command
$tenant = $this->argument('tenant'); - get the tenant from the command
tenancy()->initialize($tenant); - initialize the tenant connection
$user = User::where('email', $email)->first(); - get the user from the email
```
From here you can reset the password and or email the user with the new password or a reset link

## Authors

- [@hazelbag](https://www.github.com/hazelbag)
