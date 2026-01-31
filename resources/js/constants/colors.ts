import type {
    CustomerStatus,
    CustomerType,
    DeviceStatus,
    InvoiceStatus,
    OrderStatus,
    Priority,
    StateColor,
    TaskStatus,
    TicketStatus,
    UserStatus,
} from '@/types';

export const PRIORITY_COLOR_MAP: Record<Priority, StateColor> = {
    low: 'info',
    medium: 'neutral',
    high: 'error',
};

export const USER_STATUS_COLOR_MAP: Record<UserStatus, StateColor> = {
    active: 'info',
    suspended: 'warning',
    terminated: 'neutral',
};

export const CUSTOMER_STATUS_COLOR_MAP: Record<CustomerStatus, StateColor> = {
    active: 'info',
    suspended: 'warning',
    archived: 'neutral',
};

export const CUSTOMER_TYPE_COLOR_MAP: Record<CustomerType, StateColor> = {
    individual: 'neutral',
    business: 'info',
};

export const DEVICE_STATUS_COLOR_MAP: Record<DeviceStatus, StateColor> = {
    received: 'neutral',
    in_service: 'info',
    ready: 'success',
    delivered: 'neutral',
};

export const TICKET_STATUS_COLOR_MAP: Record<TicketStatus, StateColor> = {
    created: 'neutral',
    in_progress: 'info',
    on_hold: 'warning',
    resolved: 'success',
    closed: 'neutral',
    cancelled: 'error',
};

export const TASK_STATUS_COLOR_MAP: Record<TaskStatus, StateColor> = {
    created: 'neutral',
    completed: 'success',
    cancelled: 'error',
};

export const ORDER_STATUS_COLOR_MAP: Record<OrderStatus, StateColor> = {
    created: 'neutral',
    shipped: 'info',
    received: 'success',
    cancelled: 'error',
};

export const INVOICE_STATUS_COLOR_MAP: Record<InvoiceStatus, StateColor> = {
    draft: 'neutral',
    issued: 'info',
    sent: 'warning',
    paid: 'success',
    refunded: 'neutral',
    cancelled: 'error',
};
