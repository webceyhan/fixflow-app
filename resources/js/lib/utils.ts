import type { Icon, Option } from '@/types';
import type { InertiaLinkProps } from '@inertiajs/vue3';
import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function toOptions<T extends string>(
    record: Record<string, T>,
    iconRecord?: Record<T, Icon>,
): Option<T>[] {
    return Object.entries(record).map(([label, value]) => ({
        label: label.replace(/([a-z])([A-Z])/g, '$1 $2'),
        value,
        icon: iconRecord?.[value],
    }));
}

/**
 * Converts MySQL date format to a user-friendly format.
 * Also compatible with native input of date type.
 *
 * i.e.: 2025-05-03T00:00:00.000000Z => 2025-05-03
 */
export const toIsoDate = (mysqlDate?: string): string => {
    return mysqlDate?.split('T')[0] ?? '';
};

export function formatDate(date: string) {
    return new Date(date).toLocaleDateString('be-BE', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
    });
}

export function formatTimestamp(date: string) {
    return new Date(date).toLocaleDateString('be-BE', {
        year: 'numeric',
        month: 'numeric',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
    });
}
