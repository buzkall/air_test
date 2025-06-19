# E-commerce API Project

This is a Laravel-based e-commerce API that provides full CRUD operations for managing customers, shop item categories, shop items, and orders.

## Features

- **Customer Management**: Create, read, update, and delete customers
- **Shop Item Categories**: Manage product categories
- **Shop Items**: Manage products with pricing and category associations
- **Orders**: Create and manage orders with multiple items
- **Comprehensive Testing**: Full test coverage for all API endpoints
- **Database Seeding**: Pre-populated test data for development

## Entities

### Customer
- `id`, `name`, `surname`, `email`
- Has many orders

### ShopItemCategory
- `id`, `title`, `description`
- Belongs to many shop items

### ShopItem
- `id`, `title`, `description`, `price`
- Belongs to many categories
- Has many order items

### Order
- `id`, `customer_id`
- Belongs to customer
- Has many order items

### OrderItem
- `id`, `order_id`, `shop_item_id`, `quantity`
- Belongs to order and shop item

## Setup Instructions

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm (for assets)
- SQLite (included by default)

### Installation

1. **Clone the repository and navigate to the project directory:**
   ```bash
   cd /Users/buzkall/Code/jetbrains/air_test
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies:**
   ```bash
   npm install
   ```

4. **Set up environment file:**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations and seed test data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

## Running the Application

### Development Server

Start the Laravel development server:
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000/api`

### Using the Development Script

Alternatively, you can use the comprehensive development script that starts all services:
```bash
composer run dev
```

This command starts:
- Laravel development server
- Queue worker
- Log monitoring (Pail)
- Vite asset compilation

## API Endpoints

### Customers
- `GET /api/customers` - List all customers (paginated)
- `POST /api/customers` - Create a new customer
- `GET /api/customers/{id}` - Show specific customer with orders
- `PUT /api/customers/{id}` - Update customer
- `DELETE /api/customers/{id}` - Delete customer

### Shop Item Categories
- `GET /api/shop-item-categories` - List all categories (paginated)
- `POST /api/shop-item-categories` - Create a new category
- `GET /api/shop-item-categories/{id}` - Show specific category with items
- `PUT /api/shop-item-categories/{id}` - Update category
- `DELETE /api/shop-item-categories/{id}` - Delete category

### Shop Items
- `GET /api/shop-items` - List all shop items (paginated)
- `POST /api/shop-items` - Create a new shop item
- `GET /api/shop-items/{id}` - Show specific shop item with categories and orders
- `PUT /api/shop-items/{id}` - Update shop item
- `DELETE /api/shop-items/{id}` - Delete shop item

### Orders
- `GET /api/orders` - List all orders (paginated)
- `POST /api/orders` - Create a new order with items
- `GET /api/orders/{id}` - Show specific order with customer and items
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order

## API Request Examples

### Create a Customer
```bash
curl -X POST http://localhost:8000/api/customers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John",
    "surname": "Doe", 
    "email": "john.doe@example.com"
  }'
```

### Create a Shop Item with Categories
```bash
curl -X POST http://localhost:8000/api/shop-items \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Laptop",
    "description": "High-performance laptop",
    "price": 999.99,
    "category_ids": [1, 2]
  }'
```

### Create an Order
```bash
curl -X POST http://localhost:8000/api/orders \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "items": [
      {
        "shop_item_id": 1,
        "quantity": 2
      },
      {
        "shop_item_id": 2,
        "quantity": 1
      }
    ]
  }'
```

## Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Customer API tests
./vendor/bin/pest tests/Feature/Api/CustomerApiTest.php

# Shop Item Category API tests  
./vendor/bin/pest tests/Feature/Api/ShopItemCategoryApiTest.php

# Shop Item API tests
./vendor/bin/pest tests/Feature/Api/ShopItemApiTest.php

# Order API tests
./vendor/bin/pest tests/Feature/Api/OrderApiTest.php
```

### Run Tests with Coverage
```bash
./vendor/bin/pest --coverage
```

### Using Composer Script
```bash
composer run test
```

## Test Data

The application comes with pre-seeded test data:
- 50 customers with realistic names and emails
- 10 shop item categories (Electronics, Clothing, Books, etc.)
- 30 shop items with random prices and category associations
- 20 orders with random items and quantities

## Database

The application uses SQLite by default, with the database file located at `database/database.sqlite`. This provides:
- Zero configuration setup
- Portable database file
- Perfect for development and testing
- Full SQL feature support

## Development Features

- **Validation**: Comprehensive input validation for all endpoints
- **Relationships**: Proper Eloquent relationships with eager loading
- **Pagination**: All list endpoints are paginated (15 items per page)
- **Error Handling**: Proper HTTP status codes and error messages
- **Database Transactions**: Order creation/updates use transactions for data integrity
- **Factory Classes**: Faker-based factories for generating test data
- **Comprehensive Testing**: 100+ test cases covering all scenarios

## Architecture

- **Controllers**: RESTful API controllers in `app/Http/Controllers/Api/`
- **Models**: Eloquent models with relationships in `app/Models/`
- **Routes**: API routes defined in `routes/api.php`
- **Migrations**: Database schema in `database/migrations/`
- **Seeders**: Test data population in `database/seeders/`
- **Tests**: Feature tests in `tests/Feature/Api/`

This project follows Laravel best practices and provides a solid foundation for e-commerce API development.