<script setup lang="ts">
import { SidebarInset } from '@/components/ui/sidebar';
</script>

<template>
    <SidebarInset class="overflow-x-hidden">
        <slot />
    </SidebarInset>
</template>
