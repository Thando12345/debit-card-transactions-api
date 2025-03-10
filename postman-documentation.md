# Debit Card & Transactions API Documentation

This document provides information on how to use the Debit Card and Transactions RESTful API. All API endpoints require authentication using Sanctum token.

## Authentication Endpoints

### Register User
- **URL**: `/api/register`
- **Method**: `POST`
- **Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
  }
  ```
- **Response**: Returns user data and token

### Login
- **URL**: `/api/login`
- **Method**: `POST`
- **Body**:
  ```json
  {
    "email": "john@example.com",
    "password": "password"
  }
  ```
- **Response**: Returns authentication token

### Logout
- **URL**: `/api/logout`
- **Method**: `POST`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Success message

## Debit Card Endpoints

### List All Debit Cards
- **URL**: `/api/debit-cards`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Returns all debit cards owned by the authenticated user

### Create New Debit Card
- **URL**: `/api/debit-cards`
- **Method**: `POST`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
  ```json
  {
    "card_number": "1234567890123456",
    "expiry_date": "2024-12-31",
    "cvv": "123",
    "daily_limit": 1000.00
  }
  ```
- **Response**: Returns created debit card data

### Get Specific Debit Card
- **URL**: `/api/debit-cards/{id}`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Returns specific debit card details

### Update Debit Card
- **URL**: `/api/debit-cards/{id}`
- **Method**: `PUT`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
  ```json
  {
    "daily_limit": 2000.00,
    "is_frozen": true
  }
  ```
- **Response**: Returns updated debit card data

### Delete Debit Card
- **URL**: `/api/debit-cards/{id}`
- **Method**: `DELETE`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: No content (204)

### Get Transactions for Specific Debit Card
- **URL**: `/api/debit-cards/{id}/transactions`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Returns all transactions for the specified debit card

## Debit Card Transaction Endpoints

### List All Transactions
- **URL**: `/api/debit-card-transactions`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Returns all transactions from all debit cards owned by the authenticated user

### Create Transaction
- **URL**: `/api/debit-card-transactions`
- **Method**: `POST`
- **Headers**: `Authorization: Bearer {token}`
- **Body**:
  ```json
  {
    "debit_card_id": 1,
    "amount": 50.00,
    "type": "debit",
    "status": "completed",
    "description": "Grocery shopping",
    "transaction_date": "2023-07-15 10:30:00"
  }
  ```
- **Response**: Returns created transaction data

### Get Specific Transaction
- **URL**: `/api/debit-card-transactions/{id}`
- **Method**: `GET`
- **Headers**: `Authorization: Bearer {token}`
- **Response**: Returns specific transaction details

## Testing with Postman

1. Import the collection into Postman
2. Create an environment with a variable named `token`
3. Call the login endpoint and use a script to set the token:
   ```javascript
   pm.environment.set("token", pm.response.json().token);
   ```
4. Use the token in subsequent requests:
   ```
   Authorization: Bearer {{token}}
   ```

## Access Control

The API enforces strict access controls:
- Users can only access, modify, or delete their own debit cards
- Users can only view and create transactions for their own debit cards
- Validation rules ensure data integrity