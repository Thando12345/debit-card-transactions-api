# Database and API Setup Guide

This guide provides the necessary steps to set up the database and API for this project.

## Database Setup

1. Make sure your database connection is configured correctly in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=your_password
```

2. Create the database if it doesn't exist:
```bash
mysql -u root -p
CREATE DATABASE your_database_name;
exit;
```

3. Run migrations to create all required tables (including debit_cards and transactions):
```bash
php artisan migrate:fresh
```

4. Seed the database with test data:
```bash
php artisan db:seed
```

5. Verify your database tables are created correctly:
```bash
php artisan tinker
DB::table('debit_cards')->get();
DB::table('transactions')->get();
```

## API Testing

1. Start the Laravel development server:
```bash
php artisan serve
```

2. Use the included Postman collection for testing the API endpoints.

3. For API login, use:
```
POST /api/login
{
  "email": "john34@example.com",
  "password": "password2343"
}
```

4. For web login, use:
```
POST /login
{
  "email": "john34@example.com",
  "password": "password2343"
}
```

5. After successful login, you will be redirected to the dashboard.

## Common Issues

- If you encounter database connection issues, check your `.env` file and make sure the database credentials are correct.
- Make sure your MySQL/MariaDB service is running.
- You might need to create the database manually before running migrations:
```bash
mysql -u root -p
CREATE DATABASE your_database_name;
exit;
```

## Authentication Flow

1. Register a new user:
   - POST `/api/register`
   - Required fields: name, email, password, password_confirmation

2. Login:
   - POST `/api/login` or POST `/login` (for web)
   - Required fields: email, password
   - Response includes an authentication token for API access

3. Access protected routes:
   - Include the token in the Authorization header: `Bearer your_token_here`

## Available API Endpoints

- **Authentication**: 
  - POST `/api/login` - Login user
  - POST `/api/register` - Register new user
  - POST `/api/logout` - Logout user (requires authentication)

- **Dashboard**:
  - GET `/api/dashboard` - Get dashboard data (requires authentication)

- **Debit Cards**:
  - GET `/api/debit-cards` - List all debit cards
  - POST `/api/debit-cards` - Create a new debit card
  - GET `/api/debit-cards/{id}` - Get a specific debit card
  - PUT `/api/debit-cards/{id}` - Update a debit card
  - DELETE `/api/debit-cards/{id}` - Delete a debit card
  - GET `/api/debit-cards/{id}/transactions` - Get transactions for a debit card

- **Transactions**:
  - GET `/api/transactions` - List all transactions