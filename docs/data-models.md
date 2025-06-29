# Data Models

## Entity Definitions

### 1. User
- **Fields**: id, name, email, phone (optional), password, role, status, created_at, updated_at
- **Roles**: admin, manager, technician (default: technician)
- **Status**: active, inactive (default: active)
- **Features**: Full-text search on name, email, phone
- **Relations**: Can be assigned to tickets

### 2. Customer
- **Fields**: name (required), company, email, phone, address, notes
- **Features**: Full-text search, unique constraints on business info
- **Relations**: Has many devices and tickets
- **Validation**: Email format, unique company names

### 3. Device  
- **Fields**: customer_id, brand, model, serial_number, type, status
- **Types**: phone, tablet, laptop, desktop, other
- **Status**: checked_in, in_repair, finished, checked_out
- **Relations**: Belongs to customer, has many tickets

### 4. Ticket
- **Fields**: customer_id, device_id, description, priority, status, due_date, total_cost, balance
- **Priority**: low, normal, high, urgent
- **Status**: new, in_progress, on_hold, resolved, closed
- **Features**: QR code generation, file uploads, digital signatures
- **Relations**: Belongs to customer/device, has many tasks/orders, has one invoice

### 5. Task
- **Fields**: ticket_id, description, cost, status, completed_at
- **Status**: new, completed, cancelled
- **Rules**: Cancelled tasks are non-billable
- **Relations**: Belongs to ticket

### 6. Order
- **Fields**: ticket_id, part_name, quantity, cost, status, ordered_at  
- **Status**: new, shipped, received, cancelled
- **Rules**: Cancelled orders are non-billable
- **Relations**: Belongs to ticket

### 7. Invoice
- **Fields**: ticket_id, total, paid_amount, due_date, status
- **Status**: pending, paid, overdue
- **Calculation**: Auto-calculated from billable tasks + orders
- **Relations**: Belongs to ticket, has many transactions

### 8. Transaction
- **Fields**: invoice_id, amount, method, notes
- **Methods**: cash, card, transfer, check
- **Relations**: Belongs to invoice

## Entity Relationships

```
users (1:n) tickets (as assigned_to)
customers (1:n) devices (1:n) tickets (1:n) tasks
                              tickets (1:n) orders  
                              tickets (1:1) invoices (1:n) transactions
```

## Query Classes

Each model has a corresponding Query class using Spatie Laravel Query Builder:

- **UserQuery** - User filtering, search by name/email/phone, role-based filtering
- **CustomerQuery** - Customer search, company filtering, with device/ticket counts
- **DeviceQuery** - Device filtering by type/status/customer, brand aggregation
- **TicketQuery** - Complex ticket filtering by status/priority/overdue, with relationships
- **TaskQuery** - Task filtering by status, ticket relationships
- **OrderQuery** - Order filtering by status, part name search
- **InvoiceQuery** - Financial record filtering, overdue detection
- **TransactionQuery** - Payment method filtering, amount ranges

## Data Flow

1. **User** creates **Customer** record
2. **Customer** has multiple **Devices** registered
3. **Device** generates **Tickets** for repairs
4. **Ticket** contains multiple **Tasks** and **Orders**
5. **Ticket** automatically creates **Invoice**
6. **Invoice** receives **Transactions** as payments
