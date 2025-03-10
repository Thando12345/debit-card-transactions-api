# Transaction API Testing Guide

This document provides detailed instructions on how to test the Transaction API endpoints.

## Prerequisites
- Ensure you have an authentication token (via login)
- Postman or similar API testing tool
- A valid debit card ID (from your account)

## API Endpoints

### 1. GET /api/transactions - List Transactions
**Description**: Retrieves all transactions for the authenticated user.

**Testing Steps**:
1. Set up a GET request to `/api/transactions`
2. Add the authentication header: `Authorization: Bearer {your_token}`
3. Execute the request
4. Verify the response:
   - Status code should be 200
   - Response body should contain an array of transactions
   - Each transaction should include fields like id, amount, type, status, etc.

### 2. POST /api/transactions - Create Transaction
**Description**: Creates a new transaction.

**Testing Steps**:
1. Set up a POST request to `/api/transactions`
2. Add the authentication header: `Authorization: Bearer {your_token}`
3. Add request body in JSON format:
   ```json
   {
      "debit_card_id": 1,
      "amount": 100.00,
      "type": "debit",  // or "credit"
      "status": "completed",
      "transaction_date": "2023-11-01T12:00:00"
   }
   ```
4. Execute the request
5. Verify the response:
   - Status code should be 201 (Created)
   - Response should contain the newly created transaction details
   
**Edge Case Testing**:
- Test with a frozen card (should return 422 error)
- Test exceeding daily limit for debit transactions (should return 422 error)
- Test with invalid card ID (should return 404)
- Test with missing required fields (should return validation errors)

### 3. GET /api/transactions/{id} - Show Transaction
**Description**: Retrieves a specific transaction by ID.

**Testing Steps**:
1. Set up a GET request to `/api/transactions/{id}` (replace {id} with actual transaction ID)
2. Add the authentication header: `Authorization: Bearer {your_token}`
3. Execute the request
4. Verify the response:
   - Status code should be 200
   - Response should contain details for the requested transaction
   
**Edge Case Testing**:
- Test with non-existent ID (should return 404)
- Test with a transaction that belongs to another user (should return 403 Forbidden)

### 4. PUT /api/transactions/{id} - Update Transaction
**Description**: Updates an existing transaction.

**Testing Steps**:
1. Set up a PUT request to `/api/transactions/{id}` (replace {id} with actual transaction ID)
2. Add the authentication header: `Authorization: Bearer {your_token}`
3. Add request body with fields to update:
   ```json
   {
      "amount": 150.00,
      "status": "pending"
   }
   ```
4. Execute the request
5. Verify the response:
   - Status code should be 200
   - Response should contain the updated transaction

**Edge Case Testing**:
- Test with non-existent ID (should return 404)
- Test with a transaction that belongs to another user (should return 403 Forbidden)
- Test with invalid field values (should return validation errors)

### 5. DELETE /api/transactions/{id} - Delete Transaction
**Description**: Deletes a specific transaction.

**Testing Steps**:
1. Set up a DELETE request to `/api/transactions/{id}` (replace {id} with actual transaction ID)
2. Add the authentication header: `Authorization: Bearer {your_token}`
3. Execute the request
4. Verify the response:
   - Status code should be 204 (No Content) or 200 with a success message
   
**Edge Case Testing**:
- Test with non-existent ID (should return 404)
- Test with a transaction that belongs to another user (should return 403 Forbidden)

## Note on Route Discrepancy
The current implementation uses `/api/debit-card-transactions` routes while the specification mentions `/api/transactions`. Ensure you're using the correct endpoint based on the current implementation or discuss updating the routes to match the specification.