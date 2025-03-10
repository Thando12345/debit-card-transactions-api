# API Registration Endpoint Instructions

## Current Setup
The API routes in this application are configured with specific prefixes:
- All API routes are under the `/api` prefix (automatically added by Laravel for routes in `api.php`)
- Authentication routes are further grouped under the `auth` prefix (as seen in `api.php` line 8)

## How to Fix the 404 Error
Your current request to `/register` is not working because you need to include both prefixes.

### Update your Postman request:
1. Current (not working):
```
POST {{base_url}}/register
```

2. Correct endpoint (will work):
```
POST {{base_url}}/api/auth/register
```

### Request body (remains the same):
```json
{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

## Explanation
- The route `/register` (web route) is for browser-based form submissions
- The route `/api/auth/register` (API route) is for programmatic API access
- Your Postman request needs to use the API version of the route

No code changes are required - the routes are correctly configured. You just need to use the proper URL in your Postman request.