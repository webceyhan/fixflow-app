import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import { BookOpenIcon, FolderIcon, LayoutGridIcon } from 'lucide-vue-next';

export const MAIN_NAV_ITEMS: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGridIcon,
    },
] as const;

export const FOOTER_NAV_ITEMS: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderIcon,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpenIcon,
    },
] as const;
