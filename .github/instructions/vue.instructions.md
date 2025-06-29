---
applyTo: "**/*.vue"
---

# Vue Component Instructions

Use Vue 3 Composition API with TypeScript and `<script setup>` syntax.

Follow Inertia.js patterns for page components and props handling.

Use Vue 3 reactive state and composables for local component state management.

Implement proper prop validation with TypeScript interfaces.

Follow naming convention: PascalCase for components, kebab-case for events.

Ensure accessibility with proper ARIA attributes and semantic HTML.

Handle loading and error states appropriately.

Use Vue's reactivity system efficiently to avoid unnecessary re-renders.

Implement proper event handling and emit custom events when needed.

Use slots for flexible component composition.

Use Inertia.js useForm for server state management instead of manual form handling.

Target < 1 second page load times with optimized component loading and lazy imports.

For index/listing pages, implement query parameter management for filtering, sorting, and searching.

Use Inertia.js router.get() with preserveState: true for filter/sort operations to maintain scroll position.

Implement reactive filters using watch() to update URL parameters and trigger server requests via Inertia.js.

Support Spatie Query Builder URL patterns: filter[field]=value, sort=-field, include=relation.
