<script setup lang="ts">
import Placeholder from '@/components/Placeholder.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Head title="Dashboard" />

        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl md:gap-6"
        >
            <div
                class="grid auto-rows-min gap-4 *:aspect-video md:grid-cols-3 md:gap-6"
            >
                <Placeholder />

                <Placeholder />

                <Placeholder />
            </div>

            <Placeholder class="h-full" />
        </div>
    </AppLayout>
</template>
