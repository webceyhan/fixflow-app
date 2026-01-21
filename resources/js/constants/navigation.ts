import { DashboardIcon, DocumentationIcon, RepositoryIcon } from '@/icons';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

export const MAIN_NAV_ITEMS: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: DashboardIcon,
    },
] as const;

export const FOOTER_NAV_ITEMS: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: RepositoryIcon,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: DocumentationIcon,
    },
] as const;
