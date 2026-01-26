export const PRIORITY = {
    Low: 'low',
    Medium: 'medium',
    High: 'high',
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

export const CUSTOMER_TYPE = {
    Individual: 'individual',
    Business: 'business',
} as const;

export const CUSTOMER_STATUS = {
    Active: 'active',
    Suspended: 'suspended',
    Archived: 'archived',
} as const;

export const DEVICE_TYPE = {
    Phone: 'phone',
    Tablet: 'tablet',
    Laptop: 'laptop',
    Desktop: 'desktop',
    Wearable: 'wearable',
    Other: 'other',
} as const;

export const DEVICE_STATUS = {
    Received: 'received',
    InService: 'in_service',
    Ready: 'ready',
    Delivered: 'delivered',
} as const;

export const TICKET_STATUS = {
    Created: 'created',
    InProgress: 'in_progress',
    OnHold: 'on_hold',
    Resolved: 'resolved',
    Closed: 'closed',
    Cancelled: 'cancelled',
} as const;
