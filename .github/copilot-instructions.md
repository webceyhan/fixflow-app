FixFlow is a Laravel 12 + Inertia.js + Vue 3 + TypeScript device repair management system with MySQL database.

Use database triggers for counter updates instead of Laravel observers to eliminate N+1 queries, so avoid manual counter calculations in models.

Use shadcn/ui components with Tailwind CSS 4, so import UI components from `@/components/ui/` and use class-variance-authority for component variants.

Use Service classes for business logic instead of fat controllers, so delegate complex operations to Service classes and keep controllers thin.

Use computed columns in MySQL for real-time calculations like progress percentages and status determination, so avoid calculating these in PHP.

Handle device repair workflows where Customers have Devices that generate Tickets containing Tasks and Orders, leading to Invoices with Transactions.

Use TypeScript extensively, so always define proper interfaces for props, API responses, and form data.

Use Inertia.js useForm for server state management, so use this instead of manual form handling in Vue components.

Prioritize performance with target of less than 10 database queries per page load and less than 1 second page load times.
