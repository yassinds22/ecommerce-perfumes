# Standardized Responses

The Luxe Parfum API utilizes a predictable response structure to simplify client-side parsing.

### ✅ Success Response (200 OK)
```json
{
    "status": "success",
    "message": "Operation successful",
    "data": { ... }
}
```

### ❌ Authentication Error (401 Unauthorized)
Thrown when a token is missing, expired, or invalid.
**Use Case**: User forgot to login or token expired.
```json
{
    "status": "error",
    "message": "Unauthenticated",
    "errors": null
}
```

### 🛡️ Authorization Error (403 Forbidden)
Thrown when the authenticated user does not have permission for the resource.
**Use Case**: User trying to delete a review written by someone else.
```json
{
    "status": "error",
    "message": "This action is unauthorized.",
    "errors": null
}
```

### 📝 Validation Error (422 Unprocessable Content)
Thrown when the request body fails validation rules.
**Use Case**: Missing required email field or password too short.
```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### 🔥 Server Error (500 Internal Server Error)
Thrown when an unexpected error occurs on the backend.
```json
{
    "status": "error",
    "message": "An unexpected error occurred. Please try again later.",
    "errors": null
}
```
