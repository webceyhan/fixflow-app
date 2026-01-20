import type { Icon, Priority } from '@/types';
import { ChevronsDown, ChevronsRight, ChevronsUp } from 'lucide-vue-next';

export const PRIORITY_ICON_MAP: Record<Priority, Icon> = {
    low: ChevronsDown,
    medium: ChevronsRight,
    high: ChevronsUp,
};
