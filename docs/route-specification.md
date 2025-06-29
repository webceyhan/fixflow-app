# Web Routes Specification

## Laravel Resource Routes (Inertia.js)

All main entities follow Laravel resource conventions returning Inertia.js responses:

### Standard CRUD Resources
- **Users**: `Route::resource('users', UserController::class)` *(Admin only)*
- **Customers**: `Route::resource('customers', CustomerController::class)`
- **Devices**: `Route::resource('devices', DeviceController::class)`
- **Tickets**: `Route::resource('tickets', TicketController::class)`
- **Tasks**: `Route::resource('tasks', TaskController::class)`
- **Orders**: `Route::resource('orders', OrderController::class)`
- **Invoices**: `Route::resource('invoices', InvoiceController::class)`
- **Transactions**: `Route::resource('transactions', TransactionController::class)`

This provides the standard endpoints: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

**Response Type**: All routes return Inertia.js responses providing stateful SPA experience with Vue 3 components

## Authentication Routes

### Laravel Breeze Routes
- `POST /login` - User login
- `POST /logout` - User logout
- `GET /profile` - Current user profile
- `PUT /profile` - Update profile

## Custom/Additional Endpoints

## Search, Filtering & Sorting

All resources use [Spatie Laravel Query Builder](https://spatie.be/docs/laravel-query-builder/v6/introduction) for consistent query operations:

### Filtering
- `?filter[field]=value` - Exact match filtering
- `?filter[search]=query` - Full-text search across relevant fields
- `?filter[status]=active` - Status filtering with enum values
- `?filter[priority]=high` - Priority filtering

### Sorting
- `?sort=field` - Sort ascending by field
- `?sort=-field` - Sort descending by field (note the `-` prefix)
- `?sort=field1,-field2` - Multiple field sorting

### Including Relations
- `?include=relation` - Include related models
- `?include=relation1,relation2` - Multiple relations
- `?include=relation.nested` - Nested relations

### Field Selection
- `?fields[users]=id,name,email` - Select specific fields per resource
- `?fields[tickets]=id,description,status` - Reduce payload size

### Example Query Patterns
```
# Complex ticket filtering
GET /tickets?filter[status]=in_progress&filter[priority]=high&sort=-created_at&include=customer,device

# Customer search with device count
GET /customers?filter[search]=john&include=devices&sort=name

# Device filtering by customer and type
GET /devices?filter[customer_id]=123&filter[type]=phone&sort=-created_at
```

### Query Classes
Each resource has a dedicated Query class extending QueryBuilder:
- `TicketQuery` - Handles ticket filtering, searching, and sorting
- `CustomerQuery` - Customer-specific query operations
- `DeviceQuery` - Device filtering and relationship loading
- `UserQuery` - User management queries (Admin only)
- `InvoiceQuery` - Financial record queries

### Ticket-Specific Actions
- `PATCH /tickets/{id}/assign` - Assign ticket to technician
- `PATCH /tickets/{id}/complete` - Mark ticket as completed
- `GET /tickets/{id}/qr` - Generate QR code for ticket

### Task/Order Status Updates
- `PATCH /tasks/{id}/complete` - Mark task completed
- `PATCH /tasks/{id}/cancel` - Cancel task (non-billable)
- `PATCH /orders/{id}/receive` - Mark order received
- `PATCH /orders/{id}/cancel` - Cancel order (non-billable)

### File Management
- `POST /tickets/{id}/files` - Upload files
- `GET /tickets/{id}/files` - List files
- `GET /files/{filename}` - Download file

### Nested Resource Routes
- `Route::resource('tickets.tasks', TaskController::class)`
- `Route::resource('tickets.orders', OrderController::class)`
- `Route::resource('invoices.transactions', TransactionController::class)`

## Response Formats

### Success Response
```json
{
  "data": { ... },
  "message": "Operation successful",
  "status": "success"
}
```

### Error Response
```json
{
  "errors": {
    "field": ["Validation error message"]
  },
  "message": "Validation failed",
  "status": "error"
}
```

### Pagination Response
```json
{
  "data": [...],
  "links": {
    "first": "url",
    "last": "url",
    "prev": null,
    "next": "url"
  },
  "meta": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```
