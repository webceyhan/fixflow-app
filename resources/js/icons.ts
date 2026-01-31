import {
    CUSTOMER_TYPE_ICON_MAP,
    DEVICE_TYPE_ICON_MAP,
    PRIORITY_ICON_MAP,
    TASK_TYPE_ICON_MAP,
    TRANSACTION_METHOD_ICON_MAP,
    TRANSACTION_TYPE_ICON_MAP,
    USER_ROLE_ICON_MAP,
} from '@/constants';
import type {
    CustomerType,
    DeviceType,
    Priority,
    TaskType,
    TransactionMethod,
    TransactionType,
    UserRole,
} from '@/types';
import { h } from 'vue';

export {
    IdCard as CustomerIcon,
    LayoutGrid as DashboardIcon,
    HardDrive as DeviceIcon,
    BookOpen as DocumentationIcon,
    ReceiptText as InvoiceIcon,
    LogOut as LogOutIcon,
    Package as OrderIcon,
    Folder as RepositoryIcon,
    Settings as SettingsIcon,
    ClipboardList as TaskIcon,
    Ticket as TicketIcon,
    ArrowRightLeft as TransactionIcon,
    Users as UserIcon,
} from 'lucide-vue-next';

export const PriorityIcon = ({ value }: { value: Priority }) =>
    h(PRIORITY_ICON_MAP[value]);

export const UserRoleIcon = ({ value }: { value: UserRole }) =>
    h(USER_ROLE_ICON_MAP[value]);

export const CustomerTypeIcon = ({ value }: { value: CustomerType }) =>
    h(CUSTOMER_TYPE_ICON_MAP[value]);

export const DeviceTypeIcon = ({ value }: { value: DeviceType }) =>
    h(DEVICE_TYPE_ICON_MAP[value]);

export const TaskTypeIcon = ({ value }: { value: TaskType }) =>
    h(TASK_TYPE_ICON_MAP[value]);

export const TransactionTypeIcon = ({ value }: { value: TransactionType }) =>
    h(TRANSACTION_TYPE_ICON_MAP[value]);

export const TransactionMethodIcon = ({
    value,
}: {
    value: TransactionMethod;
}) => h(TRANSACTION_METHOD_ICON_MAP[value]);
