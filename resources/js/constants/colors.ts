import type {
    CustomerStatus,
    CustomerType,
    Priority,
    StateColor,
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
