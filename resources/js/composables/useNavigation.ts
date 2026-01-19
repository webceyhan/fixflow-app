import { FOOTER_NAV_ITEMS, MAIN_NAV_ITEMS } from '@/constants';
import { toUrl } from '@/lib/utils';
import { computed } from 'vue';
import { useActiveUrl } from './useActiveUrl';

const { urlIsActive } = useActiveUrl();

const mainNavItems = computed(() =>
    MAIN_NAV_ITEMS.map((item) => ({
        ...item,
        isActive: urlIsActive(item.href),
    })),
);

const footerNavItems = computed(() =>
    FOOTER_NAV_ITEMS.map((item) => ({
        ...item,
        href: toUrl(item.href),
    })),
);

export function useNavigation() {
    return {
        mainNavItems,
        footerNavItems,
    };
}
