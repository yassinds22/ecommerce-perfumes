# Authentication

To access protected endpoints in the Luxe Parfum API, you must provide a **Bearer Token** in the `Authorization` header of your HTTP requests.

### 🔑 Obtaining a Token
Tokens are issued upon successful login or registration via the following endpoints:
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/register`

### 🛡️ Usage Example
Pass the token in the header as follows:

```http
Authorization: Bearer <your_access_token>
```

<aside class="notice">
Tokens generated through this API are managed by <b>Laravel Sanctum</b>. They represent a secure session for a specific user and should be kept confidential.
</aside>

### 🚦 Rate Limiting
- **Authenticated Users**: 120 requests per minute.
- **Guest Users**: 60 requests per minute.
- Sensitive endpoints (like Login/Checkout) have stricter limits to prevent abuse.
