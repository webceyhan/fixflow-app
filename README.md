# FixFlow - Device Repair Management System

A modern device repair management system built with Laravel 12, Inertia.js, Vue 3, and TypeScript. Features customer management, ticket tracking, inventory control, and invoicing with role-based access control and performance optimization.

## 🚀 Quick Start

1. **Clone Repository**: `git clone https://github.com/webceyhan/fixflow-app.git`
2. **Install Dependencies**: `composer install && npm install`
3. **Environment Setup**: Copy `.env.example` to `.env` and configure database
4. **Database Setup**: `php artisan migrate --seed`
5. **Build Assets**: `npm run build` or `npm run dev` for development
6. **Start Server**: `php artisan serve`

## 📊 System Overview

**Purpose**: Complete device repair workflow management  
**Entities**: User → Customer → Device → Ticket → Task/Order → Invoice → Transaction  
**Features**: User management, repair tracking, financial processing, QR codes, file uploads

## 🛠️ Tech Stack

**Backend**: Laravel 12, PHP 8.2+, MySQL, Service layer pattern  
**Frontend**: Vue 3, TypeScript, Inertia.js, shadcn/ui, Tailwind CSS 4  
**Testing**: Pest framework  
**Performance**: < 1s page load, < 10 queries per page, > 80% test coverage

## 📋 Documentation

- [System Overview](docs/system-overview.md) - Architecture and design
- [Data Models](docs/data-models.md) - Entity relationships
- [Business Rules](docs/business-rules.md) - Workflow logic
- [Development Roadmap](docs/development-roadmap.md) - Implementation guide
- [Technical Guidelines](.github/instructions/) - Coding standards

## 🔗 Resources

- [GitHub Repository](https://github.com/webceyhan/fixflow-app)
- [Project Board](https://github.com/users/webceyhan/projects/6)
- [Issues & Milestones](https://github.com/webceyhan/fixflow-app/issues)
