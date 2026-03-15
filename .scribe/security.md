# Security & Data Integrity

Security is baked into the DNA of the Luxe Parfum API. We utilize industry-standard protocols to protect user data and ensure system availability.

### 🔑 Authentication (Laravel Sanctum)
The API uses **Laravel Sanctum** for stateless, token-based authentication.
- **Issuance**: Tokens are issued via the `/auth/login` endpoint.
- **Statelessness**: No session cookies are used; every request must carry a Bearer token.
- **Revocation**: Tokens can be revoked globally or per-device, ensuring account safety.

### 🚦 Rate Limiting & Throttling
To protect against DDoS and brute-force attacks, we implement granular rate limiting:
- **Default**: 60 requests/minute for guest users.
- **Authenticated**: 120 requests/minute for logged-in users.
- **Sensitive**: Login and Register routes utilize stricter limits.

### 🛡️ Input Sanitization & Validation
- All inputs are strictly typed and validated via Form Requests.
- SQL Injection protection is handled automatically by Eloquent's parameterized queries.
- XSS protection is implemented by sanitizing all incoming string data.

### 🔒 Encryption & Hashing
- **Passwords**: Hashed using **Argon2id** (the industry winner).
- **Communication**: HSTS and TLS-only policies are enforced.
- **Sensitive Data**: Payment details are never stored on our servers; they are handled via Stripe's encrypted engine.
