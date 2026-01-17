import { PRIORITY_ICON_MAP, USER_ROLE_ICON_MAP } from '@/constants';
import type { Priority, UserRole } from '@/types';
import { h } from 'vue';

export {
    LayoutGrid as DashboardIcon,
    BookOpen as DocumentationIcon,
    LogOut as LogOutIcon,
    Folder as RepositoryIcon,
    Settings as SettingsIcon,
    Users as UserIcon,
} from 'lucide-vue-next';

export const PriorityIcon = ({ value }: { value: Priority }) =>
    h(PRIORITY_ICON_MAP[value]);

export const UserRoleIcon = ({ value }: { value: UserRole }) =>
    h(USER_ROLE_ICON_MAP[value]);
