# CCJ-MIS-BACKEND

## Prerequisites

-   [NodeJS](https://nodejs.org/en)
-   [Laravel](https://laravel.com/docs/10.x)
-   [Composer](https://getcomposer.org/)

### Local Setup

Clone the repository

```sh
git https://github.com/itsmitchyyyy/CCJ-MIS-BACKEND.git
```

Install the dependencies.

```sh
cd ccj-mis-backend
composer install
```

In your root directory, copy the contents of `.env.example` inside `.env` file.

```sh
cp .env.example .env
```

Generate Key

```sh
php artisan key:generate
```

Migrate and Seed Database

```sh
php artisan migrate:fresh --seed
```

## Running the project

```sh
php artisan serve
```
