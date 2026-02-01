import {
    AdjustmentIcon,
    CustomerIcon,
    DashboardIcon,
    DeviceIcon,
    DocumentationIcon,
    InvoiceIcon,
    OrderIcon,
    RepositoryIcon,
    TaskIcon,
    TicketIcon,
    TransactionIcon,
    UserIcon,
} from '@/icons';
import { dashboard } from '@/routes';
import { index as adjustments } from '@/routes/adjustments';
import { index as customers } from '@/routes/customers';
import { index as devices } from '@/routes/devices';
import { index as invoices } from '@/routes/invoices';
import { index as orders } from '@/routes/orders';
import { index as tasks } from '@/routes/tasks';
import { index as tickets } from '@/routes/tickets';
import { index as transactions } from '@/routes/transactions';
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
    {
        title: 'Devices',
        href: devices(),
        icon: DeviceIcon,
    },
    {
        title: 'Tickets',
        href: tickets(),
        icon: TicketIcon,
    },
    {
        title: 'Tasks',
        href: tasks(),
        icon: TaskIcon,
    },
    {
        title: 'Orders',
        href: orders(),
        icon: OrderIcon,
    },
    {
        title: 'Invoices',
        href: invoices(),
        icon: InvoiceIcon,
    },
    {
        title: 'Transactions',
        href: transactions(),
        icon: TransactionIcon,
    },
    {
        title: 'Adjustments',
        href: adjustments(),
        icon: AdjustmentIcon,
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
