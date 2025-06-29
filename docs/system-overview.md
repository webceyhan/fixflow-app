# System Overview

## Purpose
Device repair management system that digitalizes and automates the complete repair workflow from customer intake to final delivery.

## Technical Stack

### Backend
- **Framework**: Laravel 12 with PHP 8.2+
- **Database**: MySQL with computed columns and indexes
- **Architecture**: Service layer pattern, backed enums
- **Testing**: Pest framework for feature and unit tests

### Frontend  
- **Framework**: Vue 3 with Composition API + TypeScript
- **State Management**: Inertia.js for server state (stateful SPA experience), Vue 3 reactive state for client state
- **UI Framework**: shadcn/ui components with Tailwind CSS 4
- **Build Tool**: Vite with code splitting and optimization

## Key Architectural Decisions
- **Service classes** for business logic instead of fat controllers
- **Laravel observers** for counter updates and model event handling
- **Computed columns** in MySQL for real-time calculations
- **Performance target**: < 10 database queries per page, < 1 second load times

## Core Workflow
1. **User Management** → **Customer Registration** → **Device Registration** → **Ticket Creation**
2. **Task Assignment** → **Parts Ordering** → **Work Completion** 
3. **Quality Check** → **Customer Notification** → **Payment & Delivery**

## User Roles
- **Admin**: Full system access, user management, all operations
- **Manager**: Operational access, reporting, assignment management
- **Technician**: Ticket/task management, customer interaction, limited access

## Key Features
- Real-time status synchronization
- QR code generation and tracking  
- File uploads and digital signatures
- Automated billing and invoicing
- Full-text search capabilities
- Role-based access control
