import type { Priority, StateColor, UserStatus } from '@/types';

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
