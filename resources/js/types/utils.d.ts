import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export type Icon = LucideIcon;

export type Href = NonNullable<InertiaLinkProps['href']>;

export interface Option<T = any> {
    value: T;
    label: string;
    icon?: Icon;
}

export interface NavItem {
    title: string;
    href: Href;
    icon?: Icon;
    isActive?: boolean;
}

export interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}
