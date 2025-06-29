---
applyTo: "**/tests/**/*.php"
---

# Pest Testing Instructions

Use Pest testing framework with Laravel integration and built-in expectations.

Write Feature tests for complete user workflows and web endpoints using test() function.

Write Unit tests for individual classes and methods in isolation using it() function.

Use RefreshDatabase trait or database transactions for test isolation.

Create test factories and use fake() helper for consistent test data generation.

Use descriptive test names that explain the scenario being tested.

Follow AAA pattern: Arrange, Act, Assert in test structure.

Test both positive and negative scenarios including edge cases.

Use Laravel's HTTP testing methods with Pest expectations for web endpoint testing.

Test validation rules and error responses thoroughly using expect() assertions.

Use expect() for assertions on response status codes, JSON structure, and database state.

Keep tests independent and avoid test interdependencies.

Use Pest's built-in helpers like actingAs(), seed(), and withoutExceptionHandling().
