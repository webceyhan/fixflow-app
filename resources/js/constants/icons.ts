import type {
    CustomerType,
    DeviceType,
    Icon,
    Priority,
    UserRole,
} from '@/types';
import {
    Building2,
    ChevronsDown,
    ChevronsRight,
    ChevronsUp,
    Computer,
    HardDrive,
    IdCard,
    Laptop,
    ShieldUser,
    Smartphone,
    Tablet,
    UserCog,
    UserPen,
    Watch,
} from 'lucide-vue-next';

export const PRIORITY_ICON_MAP: Record<Priority, Icon> = {
    low: ChevronsDown,
    medium: ChevronsRight,
    high: ChevronsUp,
};

export const USER_ROLE_ICON_MAP: Record<UserRole, Icon> = {
    admin: ShieldUser,
    manager: UserPen,
    technician: UserCog,
};

export const CUSTOMER_TYPE_ICON_MAP: Record<CustomerType, Icon> = {
    individual: IdCard,
    business: Building2,
};

export const DEVICE_TYPE_ICON_MAP: Record<DeviceType, Icon> = {
    phone: Smartphone,
    tablet: Tablet,
    laptop: Laptop,
    desktop: Computer,
    wearable: Watch,
    other: HardDrive,
};
