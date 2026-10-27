import type { InertiaLinkProps } from '@inertiajs/vue3';
import { Icon } from './utils';

export type Href = NonNullable<InertiaLinkProps['href']>;

export type BreadcrumbItem = {
    title: string;
    href: Href;
};

export type NavItem = {
    title: string;
    href: Href;
    icon?: Icon;
    isActive?: boolean;
};
