<script setup lang="ts">
import type { Icon, SortDirection } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowDown, ArrowDownUp, ArrowUp } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    name: string;
}

const props = defineProps<Props>();

const page = usePage();

const regex = new RegExp(`sort=(-)?${props.name}`);

const state = computed<SortDirection>(() => {
    const match = page.url.match(regex);

    return !match ? 'none' : !match?.[1] ? 'asc' : 'desc';
});

const SORT_DIRECTION_ICON_MAP: Record<SortDirection, Icon> = {
    asc: ArrowUp,
    desc: ArrowDown,
    none: ArrowDownUp,
};
</script>

<template>
    <Link
        class="inline-flex items-center gap-2 text-nowrap"
        :href="$page.url"
        :data="{
            sort: state === 'asc' ? `-${name}` : name,
        }"
        preserve-scroll
    >
        <slot />

        <component :is="SORT_DIRECTION_ICON_MAP[state]" class="size-4" />
    </Link>
</template>
