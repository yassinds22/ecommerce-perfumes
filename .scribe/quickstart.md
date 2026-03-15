# Developer Quick Start

Welcome to the team! This guide will help you integrate with the Luxe Parfum API in record time.

### 📂 SDK & Tooling
We provide pre-configured assets to jumpstart your development:
- **Postman Collection**: [Download here](/docs.postman) to test endpoints without writing code.
- **OpenAPI Spec**: [Download here](/docs.openapi) to generate client SDKs for Flutter, React, or Swift.

### 🚀 Local Environment Setup
To run this project locally for testing:
1. Clone the repository.
2. Run `composer install && npm install`.
3. Configure your `.env` with a MySQL database and Redis server.
4. Run `php artisan migrate --seed` to populate the catalog.
5. Run `php artisan serve` and access the docs at `/docs`.

### 🧪 Testing Guidelines
We recommend using **PHPUnit** for API contract testing.
```bash
# Run the API battery
php artisan test --group=api
```

### 💬 Support & Feedback
If you encounter any issues or have suggestions for the API, please reach out via our GitHub repository's Issues tab.
