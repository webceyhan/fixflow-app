import type { Priority, StateColor } from '@/types';

export const PRIORITY_COLOR_MAP: Record<Priority, StateColor> = {
    low: 'info',
    medium: 'neutral',
    high: 'error',
};
