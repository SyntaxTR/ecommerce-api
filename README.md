# E-Commerce API with Cart Management System

This project is a RESTful API built with Laravel for an e-commerce platform, featuring cart management and order processing capabilities.
## Key Features

- **PHP**: The backend is developed with PHP, leveraging its powerful features for web application development.
- **Laravel**: A modern PHP framework used for rapid application development, offering clean routing, middleware, and many built-in features.
- **MySQL**: A relational database management system (RDBMS) used to store and manage application data efficiently.
- **Request/Response Logging**: All incoming requests and outgoing responses are logged for monitoring and debugging purposes. This includes details like request parameters, response status, and timestamps.
- **Caching**: Implemented caching mechanisms to improve performance and reduce redundant database queries.
- **JWT Authentication**: Secure and stateless authentication via JSON Web Tokens (JWT). Ensures only authorized users can access protected routes and resources.

## Technologies Used

- **PHP 8.x**
- **Laravel 10.x**
- **MySQL**
- **JWT (JSON Web Tokens)**
- **Cache**
- **Request/Response Logging** (via middleware)

## Getting Started

### Installation

1. Clone the repository from GitHub:

   ```bash
   git clone https://github.com/SyntaxTR/ecommerce-api.git
   ```

2. Navigate to the project directory:

   ```bash
   cd ecommerce-api
   ```

3. Install project dependencies:

   ```bash
   composer install
   ```

4. Update the dependencies:

   ```bash
   composer update
   ```

5. Copy the `.env` file and configure the database connection:

   ```bash
   copy .env.example .env
   ```

   Edit the `.env` file to include your database credentials.

6. Run database migrations:

   ```bash
   php artisan migrate
   ```

7. Generate the JWT secret key:

   ```bash
   php artisan jwt:secret
   ```

8. Start the development server:

   ```bash
   php artisan serve
   ```

Your API is now ready to use.

## Database Schema

| Table        | Columns                                                                 |
|--------------|------------------------------------------------------------------------|
| `products`   | id (PK), name (string), price (decimal), stock (integer), created_at (timestamp), updated_at (timestamp) |
| `carts`      | id (PK), user_id (FK), status (string), created_at (timestamp), updated_at (timestamp) |
| `cart_items` | id (PK), cart_id (FK), product_id (FK), quantity (integer), price (decimal), created_at (timestamp), updated_at (timestamp) |
| `orders`     | id (PK), user_id (FK), total_amount (decimal), status (string), created_at (timestamp), updated_at (timestamp) |
| `users`      | id (PK), name (string), email (string), email_verified_at (timestamp), password (string), is_admin (enum), remember_token (string), created_at (timestamp), updated_at (timestamp) |

## API Endpoints

### Authentication

| Method | Endpoint             | Description               |
|--------|-----------------------|---------------------------|
| POST   | `/api/auth/register` | Register a new user       |
| POST   | `/api/auth/login`    | Login and obtain a token  |
| POST   | `/api/auth/logout`   | Logout and invalidate token |

### Product Management

| Method | Endpoint                  | Description                     |
|--------|----------------------------|---------------------------------|
| GET    | `/api/products`           | List all products              |
| GET    | `/api/products/{id}`      | Get product details            |
| POST   | `/api/products`           | Create a new product (Admin only) |
| PUT    | `/api/products/{id}`      | Update a product (Admin only)  |
| DELETE | `/api/products/{id}`      | Delete a product (Admin only)  |

### Cart Management

| Method | Endpoint                  | Description                     |
|--------|----------------------------|---------------------------------|
| GET    | `/api/cart`               | View the current cart          |
| POST   | `/api/cart/items`         | Add an item to the cart        |
| PUT    | `/api/cart/items/{id}`    | Update an item in the cart     |
| DELETE | `/api/cart/items/{id}`    | Remove an item from the cart   |

### Order Management

| Method | Endpoint                  | Description                     |
|--------|----------------------------|---------------------------------|
| POST   | `/api/orders`             | Place a new order               |
| GET    | `/api/orders`             | List all orders                 |
| GET    | `/api/orders/{id}`        | Get details of a specific order |
| PUT    | `/api/orders/{id}/status` | Change the order status         |

## API Endpoint Examples

### Authentication

**Register a new user:**
```bash
curl --location --request POST 'http://127.0.0.1:8000/api/auth/register' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "Name" : "Your Name",
    "email" : "email@email.com",
    "password" : "password",
    "password_confirmation" : "password"
}'
```

**Login:**
```bash
curl --location --request POST 'http://127.0.0.1:8000/api/auth/login' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email" : "email",
    "password" : "password"
}'
```

**Logout:**
```bash
curl --location --request POST 'http://127.0.0.1:8000/api/auth/logout' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

### Product Management

**List all products:**
```bash
curl --location --request GET 'http://127.0.0.1:8000/api/products/' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

**Get product details:**
```bash
curl --location --request GET 'http://127.0.0.1:8000/api/products/{id}' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

**Create a new product:**
```bash
curl --location --request POST 'http://127.0.0.1:8000/api/products' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "name": "Apple",
    "price": 15,
    "stock": 50
}'
```

**Update a product:**
```bash
curl --location --request PUT 'http://127.0.0.1:8000/api/products/{id}' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "name": "Apple",
    "price": 15,
    "stock": 50
}'
```

**Delete a product:**
```bash
curl --location --request DELETE 'http://127.0.0.1:8000/api/products/{id}' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

### Cart Management

**View the current cart:**
```bash
curl --location --request GET 'http://127.0.0.1:8000/api/cart' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

**Add an item to the cart:**
```bash
curl --location --request POST 'http://127.0.0.1:8000/api/cart/items' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "product_id": "1",
    "quantity": 10
}'
```

**Update an item in the cart:**
```bash
curl --location --request PUT 'http://127.0.0.1:8000/api/cart/items/{id}' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "quantity": 10
}'
```

**Remove an item from the cart:**
```bash
curl --location --request DELETE 'http://127.0.0.1:8000/api/cart/items/{id}' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

### Order Management

**Place a new order:**
```bash
curl --location --request POST 'http://127.0.0.1:8000/api/orders' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

**List all orders:**
```bash
curl --location --request GET 'http://127.0.0.1:8000/api/orders' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

**Get order details:**
```bash
curl --location --request GET 'http://127.0.0.1:8000/api/orders/{id}' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

**Change order status:**
```bash
curl --location --request PUT 'http://127.0.0.1:8000/api/orders/{id}/status' \
--header 'Authorization: Bearer {jwt_token}' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data '{
    "status": "shipped"
}'
```
