---
applyTo: "**/migrations/*.php"
---

# Database Migration Instructions

Use descriptive migration names following Laravel's naming convention.

Always include both up() and down() methods for reversible migrations.

Use appropriate column types with proper constraints and indexes.

Add foreign key constraints with proper cascade behavior.

Include timestamps, soft deletes, and audit fields where appropriate.

Use database-level defaults and constraints for data integrity.

Add indexes for frequently queried columns and foreign keys.

Use migration dependencies and proper ordering for related tables.

Include comments for complex columns or business logic constraints.

Test migrations in both directions (up and down) before committing.

Use Laravel's schema builder methods instead of raw SQL where possible.

Consider performance impact of migrations on large datasets.

Use database transactions for complex migrations.

Document any manual steps required after migration deployment.
