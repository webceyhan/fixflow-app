<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

interface Props {
    head?: string;
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <header class="mb-8 grid grid-cols-[1fr_auto] items-center gap-1">
        <Head :title="head ?? title" />

        <h2 class="col-start-1 text-2xl font-bold tracking-tight">
            {{ title }}
        </h2>

        <p
            v-if="description"
            class="col-start-1 row-start-2 text-sm text-muted-foreground"
        >
            {{ description }}
        </p>

        <!-- Action -->
        <div class="col-start-2 row-span-2">
            <slot />
        </div>
    </header>
</template>
