# REST Article Service

[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4.svg?style=flat&logo=php)](https://php.net/)
[![Lumen](https://img.shields.io/badge/Lumen-11.0-E74430.svg?style=flat&logo=laravel)](https://lumen.laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1.svg?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Containerized-2496ED.svg?style=flat&logo=docker&logoColor=white)](https://www.docker.com/)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-Testing-3C9CD7.svg?style=flat&logo=php)](https://phpunit.de/)

A modern, lightweight RESTful API service built with Lumen framework for managing articles and tags. The service provides comprehensive CRUD operations with advanced filtering capabilities, following clean architecture principles and best practices.

## 🚀 Features

- **Full CRUD Operations**: Complete management of articles and tags
- **Advanced Filtering**: Filter articles by multiple tags with flexible criteria
- **Clean Architecture**: Repository pattern, dependency injection, and service layers
- **Type Safety**: PHPStan-compliant code with comprehensive type annotations
- **Comprehensive Testing**: Full test coverage with PHPUnit
- **Containerized Deployment**: Docker-based infrastructure for consistent environments
- **Modern PHP**: Built with PHP 8.4 and latest language features
- **Standardized Responses**: Consistent API response format across all endpoints

## 📋 API Endpoints

### Articles
- `POST /articles` - Create a new article with tags
- `PUT /articles/{id}` - Update an existing article
- `DELETE /articles/{id}` - Delete an article
- `GET /articles/{id}` - Get article by ID with all associated tags
- `GET /articles` - List all articles with optional tag filtering

### Tags
- `POST /tags` - Create a new tag
- `PUT /tags/{id}` - Update an existing tag
- `GET /tags/{id}` - Get tag by ID
- `GET /tags` - List all tags

### Filtering Examples
```bash
# Get articles with specific tags
GET /articles?tags=php,laravel

# Filter articles by name
GET /articles?name=introduction
```

## 🛠 Technology Stack

### Core Framework
- **[Lumen 11.0](https://lumen.laravel.com/)** - Micro-framework for building fast APIs
- **[PHP 8.4](https://php.net/)** - Modern PHP with latest language features
- **[Eloquent ORM](https://laravel.com/docs/eloquent)** - Database abstraction and relationships

### Infrastructure
- **[Docker](https://www.docker.com/) & Docker Compose** - Containerization and orchestration
- **[MySQL 8.0](https://www.mysql.com/)** - Primary database for data persistence
- **[Nginx 1.17](https://nginx.org/)** - Web server and reverse proxy
- **[PHP-FPM](https://php-fpm.org/)** - PHP FastCGI Process Manager

### Development Tools
- **[PHPUnit 11.0](https://phpunit.de/)** - Unit and feature testing
- **[PHPStan (Larastan)](https://github.com/larastan/larastan)** - Static analysis for type safety
- **[Laravel Pint](https://laravel.com/docs/pint)** - Code style formatting
- **[Rector](https://getrector.org/)** - Code refactoring and modernization
- **[Faker](https://fakerphp.github.io/)** - Test data generation
- **[Mockery](http://docs.mockery.io/)** - Mocking framework for tests

### Architecture Patterns
- **Repository Pattern** - Data access abstraction
- **Dependency Injection** - Loose coupling and testability
- **Service Layer** - Business logic separation
- **DTO (Data Transfer Objects)** - Type-safe data transfer
- **Validation Services** - Centralized input validation

## 📦 Installation & Setup

### Prerequisites
- Docker and Docker Compose
- Make (optional, for convenience commands)

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/Simtel/rest-article-service
   cd rest-article-service
   ```

2. **One-command setup**
   ```bash
   make install
   ```
   This command will:
   - Build Docker containers
   - Start services
   - Copy environment configuration
   - Install Composer dependencies
   - Run database migrations
   - Seed initial data

3. **Verify installation**
   ```bash
   make test
   ```

### Manual Setup

If you prefer step-by-step installation:

```bash
# Build containers
make build

# Start services
make up

# Copy environment file
make env

# Install dependencies
make composer-install

# Run migrations
make migrate

# Seed database
make db-seed
```

### Available Make Commands

```bash
make install    # Complete installation process
make up         # Start all services
make down       # Stop all services
make build      # Build Docker containers
make test       # Run test suite
make migrate    # Run database migrations
make db-seed    # Seed database with sample data
make shell      # Access PHP container shell
make logs       # View container logs
```

## 🧪 Testing

The project includes comprehensive test coverage:

```bash
# Run all tests
make test

# Run specific test file
docker-compose exec app vendor/bin/phpunit tests/ArticleTest.php

# Run tests with coverage
docker-compose exec app vendor/bin/phpunit --coverage-html coverage
```

Test categories:
- **Unit Tests** - Individual component testing
- **Feature Tests** - API endpoint testing
- **Integration Tests** - Database interaction testing

## 🔧 Development

### Code Quality Tools

```bash
# Static analysis with PHPStan
docker-compose exec app vendor/bin/phpstan analyse

# Code formatting with Pint
docker-compose exec app vendor/bin/pint

# Code refactoring with Rector
docker-compose exec app vendor/bin/rector process
```

### Database Management

```bash
# Create new migration
docker-compose exec app php artisan make:migration create_new_table

# Run migrations
make migrate

# Rollback migrations
docker-compose exec app php artisan migrate:rollback

# Fresh database with seeding
make db-fresh
```

## 🚀 Deployment

### Production Considerations

1. **Environment Configuration**
   - Set `APP_ENV=production`
   - Configure proper database credentials
   - Set up application key
   - Configure logging levels

2. **Performance Optimization**
   - Enable OPcache in PHP configuration
   - Configure proper memory limits
   - Set up database connection pooling
   - Implement caching strategies (Redis/Memcached)

3. **Security**
   - Use HTTPS in production
   - Implement rate limiting
   - Configure CORS properly
   - Set up proper firewall rules

4. **Monitoring**
   - Add application monitoring (New Relic, DataDog)
   - Configure log aggregation
   - Set up health check endpoints
   - Monitor database performance

### Docker Production Deployment

```bash
# Build production image
docker build -f docker/web.Dockerfile -t rest-article-service:latest .

# Run with production compose
docker-compose -f docker-compose.prod.yml up -d
```

## 🎯 Future Development Opportunities

### Immediate Enhancements

1. **Authentication & Authorization**
   - JWT token-based authentication
   - Role-based access control (RBAC)
   - API key management
   - OAuth2 integration

2. **Advanced Features**
   - Full-text search for articles
   - Article versioning and history
   - Bulk operations (import/export)
   - File attachments for articles
   - Article scheduling and publishing

3. **Performance Optimizations**
   - Redis caching layer
   - Database query optimization
   - API response caching
   - Database read replicas
   - CDN integration for static assets

### Architectural Improvements

1. **Microservices Evolution**
   - Split into separate services (Articles, Tags, Users)
   - Event-driven architecture with message queues
   - Service mesh implementation
   - API Gateway integration

2. **Advanced Patterns**
   - CQRS (Command Query Responsibility Segregation)
   - Event Sourcing for audit trails
   - Domain-Driven Design (DDD) implementation
   - Hexagonal Architecture

3. **Integration Capabilities**
   - REST API versioning (v1, v2)
   - GraphQL endpoint implementation
   - WebSocket support for real-time updates
   - Webhook system for external integrations

### DevOps & Infrastructure

1. **CI/CD Pipeline**
   - GitHub Actions or GitLab CI
   - Automated testing and deployment
   - Code quality gates
   - Security scanning integration

2. **Monitoring & Observability**
   - Prometheus metrics collection
   - Grafana dashboards
   - Distributed tracing (Jaeger)
   - Log aggregation (ELK stack)

3. **Scalability**
   - Kubernetes deployment
   - Horizontal Pod Autoscaling
   - Load balancing strategies
   - Database sharding

### Developer Experience

1. **Documentation**
   - OpenAPI/Swagger documentation
   - Postman collection
   - Interactive API documentation
   - Developer onboarding guides

2. **Development Tools**
   - Local development environment improvements
   - Hot reload for development
   - Debug tools integration
   - IDE extensions and helpers

## 📖 API Documentation

Detailed API documentation is available in the `/docs` directory or can be generated using:

```bash
# Generate API documentation
make docs
```

The service provides consistent JSON responses with the following structure:

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Sample Article",
    "tags": [
      {"id": 1, "name": "php"},
      {"id": 2, "name": "laravel"}
    ]
  },
  "message": "Article created successfully"
}
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests (`make test`)
5. Run code quality tools (`make lint`)
6. Commit your changes (`git commit -am 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🔗 Links

- [Laravel Documentation](https://laravel.com/docs)
- [Lumen Documentation](https://lumen.laravel.com/docs)
- [Docker Documentation](https://docs.docker.com/)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)

---

**Built with ❤️ using modern PHP practices and clean architecture principles.**
