<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';

const isOpen = usePage().props.sidebarOpen;
</script>

<template>
    <SidebarProvider :default-open="isOpen">
        <slot />
    </SidebarProvider>
</template>
