import type { CustomerType, Icon, Priority, UserRole } from '@/types';
import {
    Building2,
    ChevronsDown,
    ChevronsRight,
    ChevronsUp,
    IdCard,
    ShieldUser,
    UserCog,
    UserPen,
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
