# Stockturn API

Stock API is a  REST JSON API for the Stockturn application which provides products CRUD, bulk upsert via CSV and stock timeline.

## Tools
* [PHP](https://www.php.net/) v8.0.2
* [Laravel](https://laravel.com/) v8.29.0
* [PostgreSQL](https://www.postgresql.org/) v13.2
* [Docker](https://www.docker.com/) v20.10.5
* [docker-compose](https://docs.docker.com/compose/) v1.27.4
* [Composer](https://getcomposer.org/) v2.0.11
* [RAML](https://raml.org/) v1.0

## Configuration

1. The application uses [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum) SPA authentication which requires that the frontend and backend must use the same domain. To run local you may edit your `/etc/hosts`.

```console
127.0.0.1   local.test
127.0.0.1   api.local.test
```

The backend (this application) is hosted at `http://api.local.test:8001` and the frontend at `http://local.test:3000`.

> If you change the hosts names be sure to update the variables `SESSION_DOMAIN` and `SANCTUM_STATEFUL_DOMAINS` in the `.env` file.

## Installation

### Using local machine

1. Clone or download this repository to your machine

2. Create two databases in `PostgreSQL`: `stockturn` and `stockturn_test`

> `stockturn_test` will be used for testing and must be referenced in the `.env.testing` file `DATABASE` section.

3. Copy the content of `.env.example` to a new file `.env`

```console
cp .env.example .env
```

4. Provide your databases information to the `DATABASE` section in the `.env` and `.env.testing`

5. Follow the steps in the `Common` section bellow

6. Start the application

```console
php artisan serve
```

### Using docker

1. Clone or download this repository to your machine

2. Create the containers

```console
docker-compose up -d
```

> The application will run on host port 8001 and the database on port 3001 by default.

3. Access the `app` container

```console
docker-compose exec app bash
```

4. Copy the content of `.env.docker` to a new file `.env`

```console
cp .env.docker .env
```

5. Follow the steps in the `Common` section bellow

### Common

1. Install the dependencies

```console
composer install
```

2. Generate the application key (used for cookies)

```console
php artisan key:generate
```

3. Generate random data (Optional)

```console
php artisan db:seed
```

4. Generate admin user

```console
php artisan app:create-admin
```

> This command will ask the `name`, `email` and `password` of the user. You can use the `-D` flag to generate a default user `name:Admin`, `email:admin@example.com`, `password:secret`.

5. Generate random data (Optional)

```console
php artisan db:seed
```

6. Create a symbolic link to the storage

```console
php artisan storage:link
```

## Documentation

Navigate to `/api/doc`. This documentation was build with [RAML](https://raml.org/).

## Testing

Execute the following command in this project root directory to run the tests:

```console
php artisan test
```
