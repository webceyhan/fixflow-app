export type Priority = 'low' | 'medium' | 'high';

export type UserRole = 'admin' | 'manager' | 'technician';

export type UserStatus = 'active' | 'suspended' | 'terminated';

export type CustomerType = 'individual' | 'business';

export type CustomerStatus = 'active' | 'suspended' | 'archived';

export type DeviceType =
    | 'phone'
    | 'tablet'
    | 'laptop'
    | 'desktop'
    | 'wearable'
    | 'other';

export type DeviceStatus = 'received' | 'in_service' | 'ready' | 'delivered';

export type TicketStatus =
    | 'created'
    | 'in_progress'
    | 'on_hold'
    | 'resolved'
    | 'closed'
    | 'cancelled';
