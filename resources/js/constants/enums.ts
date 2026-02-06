// Auto-generated from PHP enums - DO NOT EDIT MANUALLY

export const ADJUSTMENT_REASON = {
    BulkService: 'bulk_service',
    Promotion: 'promotion',
    Loyalty: 'loyalty',
    Welcome: 'welcome',
    Compensation: 'compensation',
    RushService: 'rush_service',
    Delivery: 'delivery',
    LatePayment: 'late_payment',
    Cancellation: 'cancellation',
} as const;

export const ADJUSTMENT_TYPE = { Discount: 'discount', Fee: 'fee' } as const;

export const CUSTOMER_STATUS = {
    Active: 'active',
    Suspended: 'suspended',
    Archived: 'archived',
} as const;

export const CUSTOMER_TYPE = {
    Individual: 'individual',
    Business: 'business',
} as const;

export const DATE_PRESET = {
    Today: 'today',
    Yesterday: 'yesterday',
    LastWeek: 'last_week',
    LastMonth: 'last_month',
    LastYear: 'last_year',
} as const;

export const DEVICE_STATUS = {
    Received: 'received',
    InService: 'in_service',
    Ready: 'ready',
    Delivered: 'delivered',
} as const;

export const DEVICE_TYPE = {
    Phone: 'phone',
    Tablet: 'tablet',
    Laptop: 'laptop',
    Desktop: 'desktop',
    Wearable: 'wearable',
    Other: 'other',
} as const;

export const INVOICE_STATUS = {
    Draft: 'draft',
    Issued: 'issued',
    Sent: 'sent',
    Paid: 'paid',
    Refunded: 'refunded',
    Cancelled: 'cancelled',
} as const;

export const ORDER_STATUS = {
    Created: 'created',
    Shipped: 'shipped',
    Received: 'received',
    Cancelled: 'cancelled',
} as const;

export const PRIORITY = { Low: 'low', Medium: 'medium', High: 'high' } as const;

export const TASK_STATUS = {
    Created: 'created',
    Completed: 'completed',
    Cancelled: 'cancelled',
} as const;

export const TASK_TYPE = {
    Repair: 'repair',
    Diagnosis: 'diagnosis',
    Testing: 'testing',
    Calibration: 'calibration',
    Installation: 'installation',
    Upgrade: 'upgrade',
    Maintenance: 'maintenance',
    Cleaning: 'cleaning',
    Other: 'other',
} as const;

export const TICKET_STATUS = {
    Created: 'created',
    InProgress: 'in_progress',
    OnHold: 'on_hold',
    Resolved: 'resolved',
    Closed: 'closed',
    Cancelled: 'cancelled',
} as const;

export const TRANSACTION_METHOD = {
    Cash: 'cash',
    Card: 'card',
    Online: 'online',
} as const;

export const TRANSACTION_TYPE = {
    Deposit: 'deposit',
    Payment: 'payment',
    Refund: 'refund',
} as const;

export const USER_ROLE = {
    Admin: 'admin',
    Manager: 'manager',
    Technician: 'technician',
} as const;

export const USER_STATUS = {
    Active: 'active',
    Suspended: 'suspended',
    Terminated: 'terminated',
} as const;
