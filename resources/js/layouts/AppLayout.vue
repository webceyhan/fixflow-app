<script setup lang="ts">
import AppContent from '@/components/app/AppContent.vue';
import AppShell from '@/components/app/AppShell.vue';
import AppSidebar from '@/components/app/AppSidebar.vue';
import AppSidebarHeader from '@/components/app/AppSidebarHeader.vue';
import { Toaster } from '@/components/ui/sonner';
import { useToast } from '@/composables/useToast';
import type { NavItem } from '@/types';

interface Props {
    breadcrumbs?: NavItem[];
}

defineProps<Props>();

useToast().watchStatus();
</script>

<template>
    <AppShell>
        <AppSidebar />
        <AppContent>
            <AppSidebarHeader :breadcrumbs />
            <Toaster />
            <div class="h-full p-4 md:p-10">
                <slot />
            </div>
        </AppContent>
    </AppShell>
</template>
