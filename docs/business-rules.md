# Business Rules

## Status Synchronization

### Ticket Status Logic
Auto-determined by task/order completion:
- **new**: No tasks/orders started
- **in_progress**: Some tasks/orders in progress
- **on_hold**: All active tasks/orders on hold
- **resolved**: All tasks/orders completed
- **closed**: Invoice paid and device delivered

### Device Status Logic
Auto-updated based on active tickets:
- **checked_in**: Device received, ticket created
- **in_repair**: Active ticket in progress
- **finished**: All tickets resolved
- **checked_out**: Device delivered to customer

## Billing Rules

### Invoice Calculation
- **Invoice Total**: Sum of billable tasks + billable orders
- **Balance**: Invoice total - sum of transactions
- **Overdue**: Due date passed and balance > 0
- **Auto-billing**: Tasks/orders are billable unless cancelled

### Cost Management
- **Billable Items**: Completed tasks + received orders
- **Non-billable Items**: Cancelled tasks + cancelled orders
- **Approval Workflow**: High-value tasks/orders require approval
- **Real-time Updates**: Balance updates with each transaction

## Counter Maintenance

### Laravel Observers
Use Laravel observers for automatic counter maintenance and data consistency:
- **Task Counters**: TaskObserver updates Ticket task counts (pending/complete/total)
- **Order Counters**: OrderObserver updates Ticket order counts (pending/complete/total)
- **Ticket Counters**: TicketObserver updates Device ticket counts (pending/complete/total)
- **Device Counters**: DeviceObserver updates Customer device counts (pending/complete/total)
- **Invoice Financials**: TransactionObserver updates Invoice financial amounts and status

### Computed Columns
Real-time calculations using MySQL computed columns:
- **tickets.progress_percentage**: (completed_tasks / total_tasks) * 100
- **invoices.balance**: total - paid_amount  
- **tickets.is_overdue**: due_date < NOW() AND status != 'closed'

## Workflow Rules

### User Permissions
- **Admin**: Full access to all operations including user management
- **Manager**: Operational access, can view all tickets and assign technicians
- **Technician**: Can only access assigned tickets and update task status

### Data Integrity
- **Customer Required**: Cannot create device without customer
- **Device Required**: Cannot create ticket without device
- **Auto-Invoice**: Invoice created automatically when ticket is created
- **Status Validation**: Status changes must follow defined workflows

## File Management Rules

### Upload Constraints
- **File Types**: Images (jpg, png, pdf) for documentation
- **Size Limits**: Max 10MB per file, 50MB total per ticket
- **Organization**: Files organized by ticket ID in subfolders
- **Security**: File validation and virus scanning

### QR Code Generation
- **Unique Codes**: Each ticket gets unique QR code
- **Format**: SVG format for scalability
- **Storage**: Stored in public/qr/ directory
- **URL Structure**: /qr/{ticket_id}.svg
