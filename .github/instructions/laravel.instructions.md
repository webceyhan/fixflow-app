---
applyTo: "**/*.php"
---

# PHP and Laravel Instructions

Use PHP 8.2+ features including typed properties, union types, and match expressions.

Follow PSR-12 coding standards and Laravel conventions.

Use Laravel's built-in validation, authentication, and authorization features.

Implement proper error handling with try-catch blocks and custom exceptions.

Use Eloquent ORM with explicit relationships and proper query optimization.

Use Form Request classes for validation and authorization.

Implement Resource classes for consistent Inertia.js responses.

Follow single responsibility principle for controllers and services.

Use Laravel's dependency injection container appropriately.

Use Laravel's queue system for background processing.

Apply database transactions for data consistency.

Use Laravel's built-in security features and avoid SQL injection.

Use Service classes for complex business logic instead of fat controllers.

Use database triggers for counter updates instead of Laravel observers to eliminate N+1 queries.

Use computed columns in MySQL for real-time calculations like progress percentages.

Prioritize performance with target of < 10 database queries per page load.

Use Spatie Query Builder for all web interface filtering, sorting, and searching operations.

Create dedicated Query classes extending QueryBuilder for each resource with allowed filters, sorts, and includes.

Use AllowedFilter::scope() for full-text search and AllowedFilter::exact() for status filtering.

Include relationship counts and eager loading in Query classes to prevent N+1 queries.

Provide static filters() method in Query classes for frontend filter UI configuration.
