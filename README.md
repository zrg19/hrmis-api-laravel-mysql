# HRMIS API

Human Resource Management Information System API built with Laravel 11 and JWT authentication.

## Features

- **JWT Authentication** - Secure token-based authentication
- **User Management** - CRUD operations for users with role-based access control
- **Task Management** - Create, assign, and track tasks
- **Leave Management** - Request and approve leave applications
- **Role-based Access Control** - Admin, Manager, and Employee roles
- **Swagger Documentation** - Interactive API documentation

## API Documentation

### Swagger UI
Access the interactive API documentation at:
- **Local**: http://localhost:8000/api/documentation
- **Alternative**: http://localhost:8000/docs (redirects to Swagger UI)

### API Base URL
```
http://localhost:8000/api
```

## Authentication

The API uses JWT (JSON Web Token) authentication. To access protected endpoints:

1. **Login** to get a JWT token:
   ```bash
   POST /api/auth/login
   {
     "email": "user@example.com",
     "password": "password123"
   }
   ```

2. **Include the token** in subsequent requests:
   ```bash
   Authorization: Bearer <your_jwt_token>
   ```

## Available Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout (requires auth)
- `POST /api/auth/refresh` - Refresh JWT token (requires auth)

### Users
- `GET /api/users/profile` - Get authenticated user profile (requires auth)
- `GET /api/users` - Get all users (Admin/Manager only)
- `POST /api/users` - Create new user (Admin only)
- `GET /api/users/{id}` - Get user by ID (Admin/Manager only)
- `PUT /api/users/{id}` - Update user (Admin only)
- `DELETE /api/users/{id}` - Delete user (Admin only)
- `GET /api/users/email/{email}` - Find user by email (requires auth)

### Tasks
- Full CRUD operations for task management (requires auth)

### Leaves
- Full CRUD operations for leave management (requires auth)
- Additional endpoints for filtering leaves by user and status

## Role-Based Access Control

- **Admin**: Full access to all endpoints
- **Manager**: Can view users and manage tasks/leaves
- **Employee**: Limited access to own profile and assigned tasks/leaves

## Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- MySQL/PostgreSQL
- Laravel 11

### Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd hrmis-api
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Environment setup**:
   ```bash
   cp .env.example .env
   # Configure your database and other environment variables
   ```

4. **Generate JWT secret**:
   ```bash
   php artisan jwt:secret
   ```

5. **Run migrations**:
   ```bash
   php artisan migrate
   ```

6. **Start the server**:
   ```bash
   php artisan serve
   ```

7. **Access the API**:
   - API: http://localhost:8000/api
   - Documentation: http://localhost:8000/api/documentation

## Testing the API

### 1. Register a new user
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "department": "IT",
    "designation": "Developer"
  }'
```

### 2. Login to get JWT token
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 3. Use the token to access protected endpoints
```bash
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer <your_jwt_token>" \
  -H "Accept: application/json"
```

## Swagger Documentation Features

- **Interactive Testing**: Test API endpoints directly from the browser
- **Request/Response Examples**: See example data for all endpoints
- **Authentication**: Easy JWT token input for testing protected endpoints
- **Schema Definitions**: Complete data models and validation rules
- **Response Codes**: Detailed error responses and success codes

## Development

### Regenerating Swagger Documentation
After making changes to API endpoints or models:

```bash
php artisan l5-swagger:generate
```

### Adding New Endpoints
1. Add Swagger annotations to your controller methods
2. Use the `@OA\` prefix for all Swagger annotations
3. Reference existing schemas using `ref="#/components/schemas/SchemaName"`
4. Regenerate documentation after changes

## License

This project is licensed under the MIT License.

## Support

For support, email admin@hrmis.com or create an issue in the repository.
