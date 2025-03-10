# API Authentication Guide

## Issue
You're getting redirected to the Laravel login page when trying to interact with the API endpoints instead of receiving proper JSON responses.

## Solution
The API is protected using Laravel Sanctum authentication. Here's how to properly authenticate your API requests:

### 1. Login First
Before making any API requests, you need to authenticate and get a token:

```
POST /api/login
{
    "email": "your-email@example.com",
    "password": "your-password"
}
```

This will return a response with an authentication token:

```json
{
    "token": "your-auth-token"
}
```

### 2. Include the Token in Subsequent Requests
For all subsequent API requests, include the token in the Authorization header:

```
Authorization: Bearer your-auth-token
```

### 3. Example for Creating a Debit Card
```
POST /api/debit-cards
Headers:
  - Authorization: Bearer your-auth-token
  - Accept: application/json
  - Content-Type: application/json

Body:
{
    "card_number": "1234567812345678",
    "card_holder": "John Doe",
    "expiry_date": "2026-12-31",
    "cvv": "123"
}
```

### 4. Important Headers
Always include these headers with your API requests:
- `Accept: application/json` (ensures Laravel treats it as JSON request)
- `Authorization: Bearer your-auth-token` (provides authentication)

## Registration
If you don't have an account yet, you can register:

```
POST /api/register
{
    "name": "Your Name",
    "email": "your-email@example.com",
    "password": "your-password",
    "password_confirmation": "your-password"
}
```

## Notes
- The changes made to the application ensure that API endpoints respond with proper JSON even when authentication fails.
- If you get a 401 Unauthorized response, your token may have expired - try logging in again.
- For testing purposes, use tools like Postman or curl to ensure headers are properly set.