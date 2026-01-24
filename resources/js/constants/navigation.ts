import {
    CustomerIcon,
    DashboardIcon,
    DocumentationIcon,
    RepositoryIcon,
    UserIcon,
} from '@/icons';
import { dashboard } from '@/routes';
import { index as customers } from '@/routes/customers';
import { index as users } from '@/routes/users';
import type { NavItem } from '@/types';

export const MAIN_NAV_ITEMS: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: DashboardIcon,
    },
    {
        title: 'Users',
        href: users(),
        icon: UserIcon,
    },
    {
        title: 'Customers',
        href: customers(),
        icon: CustomerIcon,
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
