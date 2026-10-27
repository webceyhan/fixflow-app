import type { LucideIcon } from '@lucide/vue';

export type Icon = LucideIcon;

export type Option<T = any> = {
    value: T;
    label: string;
    icon?: Icon;
    class?: string;
    checked?: boolean;
    pinned?: boolean;
    [key: string]: any;
};

export type Paginated<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
};
