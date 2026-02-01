// Auto-generated from PHP enums - DO NOT EDIT MANUALLY

export type AdjustmentReason =
    | 'bulk_service'
    | 'promotion'
    | 'loyalty'
    | 'welcome'
    | 'compensation'
    | 'rush_service'
    | 'delivery'
    | 'late_payment'
    | 'cancellation';

export type AdjustmentType = 'discount' | 'fee';

export type CustomerStatus = 'active' | 'suspended' | 'archived';

export type CustomerType = 'individual' | 'business';

export type DeviceStatus = 'received' | 'in_service' | 'ready' | 'delivered';

export type DeviceType =
    | 'phone'
    | 'tablet'
    | 'laptop'
    | 'desktop'
    | 'wearable'
    | 'other';

export type InvoiceStatus =
    | 'draft'
    | 'issued'
    | 'sent'
    | 'paid'
    | 'refunded'
    | 'cancelled';

export type OrderStatus = 'created' | 'shipped' | 'received' | 'cancelled';

export type Priority = 'low' | 'medium' | 'high';

export type TaskStatus = 'created' | 'completed' | 'cancelled';

export type TaskType =
    | 'repair'
    | 'diagnosis'
    | 'testing'
    | 'calibration'
    | 'installation'
    | 'upgrade'
    | 'maintenance'
    | 'cleaning'
    | 'other';

export type TicketStatus =
    | 'created'
    | 'in_progress'
    | 'on_hold'
    | 'resolved'
    | 'closed'
    | 'cancelled';

export type TransactionMethod = 'cash' | 'card' | 'online';

export type TransactionType = 'deposit' | 'payment' | 'refund';

export type UserRole = 'admin' | 'manager' | 'technician';

export type UserStatus = 'active' | 'suspended' | 'terminated';
