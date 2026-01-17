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
