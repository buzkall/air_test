# Laravel Shop API

This project provides a RESTful API for managing Customers, Shop Item Categories, Shop Items, and Orders. It includes full CRUD endpoints and automated tests.

## Requirements
- PHP >= 8.1
- Composer
- A supported database (e.g., MySQL, SQLite)

## Setup Instructions

1. **Clone the repository**

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Copy and edit your environment file:**
   ```bash
   cp .env.example .env
   # Edit .env as needed (DB connection, etc.)
   ```

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   This will create all tables and populate the database with test data.

6. **Run the application:**
   ```bash
   php artisan serve
   ```
   The API will be available at `http://localhost:8000/api`.

## API Endpoints

- `GET    /api/customers`
- `POST   /api/customers`
- `GET    /api/customers/{id}`
- `PUT    /api/customers/{id}`
- `DELETE /api/customers/{id}`

- `GET    /api/shop-item-categories`
- `POST   /api/shop-item-categories`
- `GET    /api/shop-item-categories/{id}`
- `PUT    /api/shop-item-categories/{id}`
- `DELETE /api/shop-item-categories/{id}`

- `GET    /api/shop-items`
- `POST   /api/shop-items`
- `GET    /api/shop-items/{id}`
- `PUT    /api/shop-items/{id}`
- `DELETE /api/shop-items/{id}`

- `GET    /api/orders`
- `POST   /api/orders`
- `GET    /api/orders/{id}`
- `PUT    /api/orders/{id}`
- `DELETE /api/orders/{id}`

## Running Tests

Automated feature tests are provided for all endpoints:

```bash
php artisan test
```

Tests use an in-memory or test database and will not affect your production data.

---

For any questions or issues, please contact the project maintainer.
