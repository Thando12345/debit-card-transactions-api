# Debit Card & Loan Service Implementation

## Overview
This project implements a RESTful API for managing debit cards and transactions. The system enforces access control, ensuring customers can only interact with their own debit cards and transactions. Built with Laravel, it provides a comprehensive set of endpoints for creating, reading, updating, and deleting debit cards, as well as managing transactions.

The project consists of two main challenges:
1. **Challenge #1 (Implemented)**: Debit Card & Transactions API - A complete solution for managing debit cards and their transactions.
2. **Challenge #2 (Planned)**: Loan Repayment Service - This feature is planned for future development.

## Features
- User authentication and registration system
- Secure API endpoints protected with Laravel Sanctum
- Complete CRUD operations for debit cards
- Transaction management for debit cards
- Data validation and sanitization
- Access control policies

## Installation

### Prerequisites
- PHP 8.0 or higher
- Composer
- MySQL or another database supported by Laravel
- Node.js and NPM (for frontend assets, if applicable)

### Setup Steps
1. Clone the repository
   ```
   git clone https://github.com/Thando12345/debit-card-api.git
   cd debit-card-api
   ```

2. Install PHP dependencies
   ```
   composer install
   ```

3. Create a .env file
   ```
   cp .env.example .env
   ```

4. Configure your database in the .env file
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=debit_card_api
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Generate application key
   ```
   php artisan key:generate
   ```

6. Run database migrations
   ```
   php artisan migrate
   ```

7. Seed the database (optional)
   ```
   php artisan db:seed
   ```

8. Start the development server
   ```
   php artisan serve
   ```

## Authentication

The API uses Laravel Sanctum for token-based authentication. To access protected endpoints, clients must include a bearer token in the request headers.

### Registration
```
POST /api/register
```
Required parameters:
- `name`: The user's full name
- `email`: A valid and unique email address
- `password`: Password (minimum 8 characters)
- `password_confirmation`: Must match the password field

### Login
```
POST /api/login
```
Required parameters:
- `email`: The registered email address
- `password`: The user's password

Upon successful login, the API returns an authentication token that must be included in subsequent requests.

### Logout
```
POST /api/logout
```
This endpoint invalidates the current authentication token.

## API Endpoints

All API endpoints except registration and login require authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your-token}
```

### Debit Card Endpoints

#### List all debit cards
```
GET /api/debit-cards
```
Returns all debit cards owned by the authenticated user.

#### Create a new debit card
```
POST /api/debit-cards
```
Required parameters:
- `card_number`: Card number (16 digits)
- `cardholder_name`: Name of the cardholder
- `expiry_date`: Expiry date in MM/YY format
- `cvv`: 3-digit security code
- `daily_limit`: Maximum daily transaction amount

#### Get details of a specific debit card
```
GET /api/debit-cards/{id}
```
Returns detailed information about a specific debit card if owned by the authenticated user.

#### Update an existing debit card
```
PUT /api/debit-cards/{id}
```
Optional parameters (any of):
- `cardholder_name`: Updated cardholder name
- `daily_limit`: Updated daily limit
- `is_frozen`: Boolean to freeze/unfreeze the card

#### Delete a debit card
```
DELETE /api/debit-cards/{id}
```
Deletes a debit card if owned by the authenticated user.

#### Get transactions for a specific debit card
```
GET /api/debit-cards/{debitCard}/transactions
```
Returns all transactions associated with the specified debit card.

### Transaction Endpoints

#### List all transactions
```
GET /api/debit-card-transactions
```
Returns all transactions across all debit cards owned by the authenticated user.

#### Create a transaction
```
POST /api/debit-card-transactions
```
Required parameters:
- `debit_card_id`: ID of the debit card for this transaction
- `amount`: Transaction amount (numeric, positive value)
- `type`: Transaction type ('credit' or 'debit')
- `description`: Description of the transaction

#### Get details of a specific transaction
```
GET /api/debit-card-transactions/{id}
```
Returns detailed information about a specific transaction.

## Data Models

### User
- id: Primary key
- name: User's full name
- email: User's email address
- password: Hashed password
- created_at: Timestamp
- updated_at: Timestamp
- Relationships: Has many DebitCards

### DebitCard
- id: Primary key
- user_id: Foreign key to users table
- card_number: 16-digit card number
- cardholder_name: Name of the cardholder
- expiry_date: Card expiry date
- cvv: 3-digit security code
- daily_limit: Maximum daily transaction amount
- is_frozen: Boolean indicating if the card is frozen
- created_at: Timestamp
- updated_at: Timestamp
- Relationships: Belongs to User, Has many Transactions

### Transaction
- id: Primary key
- debit_card_id: Foreign key to debit_cards table
- amount: Transaction amount
- type: Transaction type (credit/debit)
- description: Description of the transaction
- created_at: Timestamp
- updated_at: Timestamp
- Relationships: Belongs to DebitCard

## Business Logic

1. Each user can have multiple debit cards
2. Each debit card can have multiple transactions
3. Cards have a daily transaction limit
4. Transactions can be of type "credit" (adding funds) or "debit" (removing funds)
5. A transaction cannot exceed the daily limit
6. Frozen cards cannot process transactions
7. Users can only access their own cards and transactions

## Testing

The application includes feature tests covering both positive and negative scenarios to ensure proper functionality.

To run the tests:
```
php artisan test
```

### Test Coverage

The test suite covers:
- User authentication and authorization
- Debit card CRUD operations
- Transaction creation and retrieval
- Business logic validation:
  - Daily limit enforcement
  - Card freezing functionality
  - Access control

## Security

- All endpoints are protected with authentication except for registration and login
- Access control policies ensure users can only access their own resources
- Input data is validated and sanitized
- Sensitive card data is masked in responses

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).