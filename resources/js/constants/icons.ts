import type { Icon, Priority, UserRole } from '@/types';
import {
    ChevronsDown,
    ChevronsRight,
    ChevronsUp,
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
