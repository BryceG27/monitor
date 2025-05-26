# Technical Test - How to fire it up

## Setting up the development environment

First, copy the `.env.example` file and rename it to `.env`:

```sh
cp .env.example .env
```

### With Docker (recommended)

**Note:** The `.env` file is configured for multi-environment port mapping. Change `*_PORT_EXPOSED` as needed.

#### Quick start

- Run:
  ```sh
  make
  ```
  This will:
  1. Start all required containers
  2. Import Node
  3. Install all Node dependencies
  4. Build the assets
  5. Open a shell in the `app` container
- In the `app` container shell, run:
  ```sh
  php artisan key:generate
  php artisan migrate
  ```

#### If Makefile does not work

Run these steps manually:

1. Install Laravel dependencies:
   ```sh
   docker compose run --rm composer install
   ```
2. Start containers:
   ```sh
   docker compose up -d
   ```
3. Enter the app container:
   ```sh
   docker exec -it app sh
   ```
4. Generate Laravel key:
   ```sh
   php artisan key:generate
   ```
5. Run migrations:
   ```sh
   php artisan migrate
   ```

### Without Docker

**Note:**
- Update `DB_HOST` to `localhost` or `127.0.0.1` instead of `DB`.
- Update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` as needed.
- For Laravel 12, SQLite is the default: comment all `DB_*` variables except `DB_CONNECTION=sqlite`.
- PHP >= 8.2 is required.
- Node and NPM are required to build assets.

#### Steps

1. Install Laravel dependencies:
   ```sh
   composer install
   ```
2. Generate app key:
   ```sh
   php artisan key:generate
   ```
3. Run migrations:
   ```sh
   php artisan migrate
   ```
4. Install Node packages:
   ```sh
   npm install
   ```
5. Build assets:
   ```sh
   npm run build
   ```

## What now?

Your app should now be ready. Visit [localhost:8000](http://localhost:8000) to check the Laravel version.

You can now use the APIs described in the **The APIs** section. Optionally, you can seed the database for easier testing:

- Create 50 products:
  ```sh
  php artisan db:seed ProductsSeeder
  ```
- Create 50 orders:
  ```sh
  php artisan db:seed OrdersSeeder
  ```
- Or both:
  ```sh
  php artisan db:seed DevSeeder
  ```

## Testing

The test environment is ready to use with Docker. If not using Docker:

- In `.env.example`, set `DB_CONNECTION=sqlite` and comment out other `DB_*` variables.
- Any change to `.env*` files requires the app container to be stopped or restarted.

Alternatively:

1. Change `DB_PORT` from `db_test` to `127.0.0.1`.
2. Create a new database (e.g., `laravel_test_db`).
3. Update `DB_USERNAME` and `DB_PASSWORD` as needed.

## Application overview

From the homepage you will see three menus: **Home**, **Products**, **Orders**.

### Products

- On page load, all non-deleted products are fetched and displayed in a table.
- To create a product, click **Create Product** and fill in the form.
- To edit a product, click the green pencil icon in the table.
- To delete a product, click the trash icon. If the product is linked to an order, deletion is not allowed.

### Orders

- On page load, all orders and non-deleted products are fetched and displayed.
- To create an order, click **Create Order** and fill in the form (name, description, date, and select products with quantities).
- The first column of the orders table is expandable and shows order items.
- To edit an order, click the green pencil icon. You can also remove items by deselecting them and saving.
- To delete an order, click the trash icon.

## Test framework

This project uses [PEST](https://pestphp.com/) for testing. All tests are in `tests/Feature`:

- **CRUDProductTest**
  1. Checks product creation
  2. Checks product update
  3. Checks product deletion
- **CRUDOrderTest**
  1. Checks order creation
  2. Checks order update
  3. Checks order deletion

## Running tests

To run all tests:

```sh
php artisan test
```

Run this command from your Docker container shell or your local repository.

---

## Resources used

### Backend

| Name      | Description                                   | Link                                      |
|-----------|-----------------------------------------------|-------------------------------------------|
| Laravel 12| PHP web framework                             | https://laravel.com/                      |
| Sanctum   | API authentication for Laravel                | https://laravel.com/docs/12.x/sanctum      |
| Composer  | Dependency manager for PHP                    | https://getcomposer.org/                   |

### Frontend

| Name        | Description                                 | Link                                      |
|-------------|---------------------------------------------|-------------------------------------------|
| Vue 3       | JavaScript framework for UI                 | https://vuejs.org/                        |
| Axios       | HTTP client for API calls                   | https://axios-http.com/                    |
| PrimeVue    | UI component library for Vue                | https://www.primefaces.org/primevue/       |
| PrimeIcons  | Icon library for PrimeVue                   | https://www.primefaces.org/primevue/showcase/#/icons |
| Moment.js   | Date formatting library                     | https://momentjs.com/                      |
| Sweetalert2 | Alert and modal library                     | https://sweetalert2.github.io/             |
| Tailwind CSS| Utility-first CSS framework                 | https://tailwindcss.com/                   |
| Node.js     | JavaScript runtime                          | https://nodejs.org/                        |
| NPM         | Node package manager                        | https://www.npmjs.com/                     |

### Development & Tooling

| Name         | Description                                 | Link                                      |
|--------------|---------------------------------------------|-------------------------------------------|
| Docker       | Containerization platform                   | https://www.docker.com/                    |
| Make         | Build automation tool                       | https://www.gnu.org/software/make/         |

---