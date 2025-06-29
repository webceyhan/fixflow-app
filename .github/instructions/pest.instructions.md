---
applyTo: "**/tests/**/*.php"
---

# Pest Testing Instructions

Use Pest testing framework with Laravel integration and built-in expectations.

## Test Function Patterns

Write Feature tests for complete user workflows and web endpoints using test() function.

Write Unit tests for individual classes and methods in isolation using it() function.

Use descriptive test names that explain the scenario being tested in plain English.

## Database and Test Isolation

Use RefreshDatabase trait for test isolation in feature tests.

Use database transactions for faster unit tests when possible.

Create test factories for all models and use fake() helper for consistent test data generation.

## Test Structure and Patterns

Follow AAA pattern: Arrange, Act, Assert in test structure.

Test both positive and negative scenarios including edge cases.

Keep tests independent and avoid test interdependencies.

Use Pest's built-in helpers like actingAs(), seed(), and withoutExceptionHandling().

## Assertions and Expectations

Use expect() for assertions on response status codes, JSON structure, and database state.

Use Laravel's HTTP testing methods with Pest expectations for web endpoint testing.

Test validation rules and error responses thoroughly using expect() assertions.

Use parameterized tests with datasets for testing multiple scenarios efficiently.

## Common Test Commands

```bash
# Run all tests
php artisan test

# Run tests with compact output  
php artisan test --compact

# Run specific test filter
php artisan test --filter="Observer"

# Run tests with coverage
php artisan test --coverage

# Run parallel tests for speed
php artisan test --parallel
```
