import { PRIORITY_ICON_MAP } from '@/constants';
import type { Priority } from '@/types';
import { h } from 'vue';

export {
    LayoutGrid as DashboardIcon,
    BookOpen as DocumentationIcon,
    LogOut as LogOutIcon,
    Folder as RepositoryIcon,
    Settings as SettingsIcon,
} from 'lucide-vue-next';

export const PriorityIcon = ({ value }: { value: Priority }) =>
    h(PRIORITY_ICON_MAP[value]);
