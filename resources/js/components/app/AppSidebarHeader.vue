<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { NavItem } from '@/types';

interface Props {
    breadcrumbs?: NavItem[];
}

defineProps<Props>();
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-4 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-10"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-2" />
            <template v-if="breadcrumbs?.length">
                <Breadcrumbs :breadcrumbs />
            </template>
        </div>
    </header>
</template>
