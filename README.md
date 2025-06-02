<h2 align="center">Lenderly - Employee Information System</h2>

## System Requirements

-   PHP 8.2 or latest
-   Composer 2.8.8
-   Laravel 12
-   MySQL 8
-   Apache/NGINX latest version

## Installation

Clone the lenderly-eis repository.

```bash
$ git clone git@github.com:rogertrocio/lenderly-eis.git
$ cd lenderly-eis
$ composer install
$ cp .env.example .env
$ php artisan key:generate
```

Create a MySQL database named lenderly_eis.

```bash
$ mysql -u user -p

create DATABASE lenderly_eis;
```

Update .env file for database configuration.

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lenderly_eis
DB_USERNAME=user
DB_PASSWORD=password
```

Migrate the database and populate the data.

```bash
$ php artisan migrate
$ php artisan db:seed
```

Create the symbolic link of storage folder to public folder.

```bash
$ php artisan storage:link
```

Optimize the application.

```bash
$ php artisan optimize
```

Serve the application in localhost.

```bash
$ php artisan serve
```

Compile and hot-reload the application for development and serve in `http://127.0.01:8000/`.

```bash
$ npm install
$ npm run dev
```

To seed a large data of the users, run this command:

```bash
$ php artisan db:seed --class=BatchUserSeeder
```

Update .env file for database configuration of PHP unit testing.

```bash
TEST_DB_CONNECTION=mysql_testing
TEST_DB_HOST=127.0.0.1
TEST_DB_PORT=3306
TEST_DB_DATABASE=lenderly_eis_test
TEST_DB_USERNAME=user
TEST_DB_PASSWORD=password
```

To run unit test, run this command:

```bash
$ php artisan test
```
