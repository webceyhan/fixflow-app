<script setup lang="ts">
interface Props {
    title?: string;
    stacked?: boolean;
}

defineProps<Props>();
</script>

<template>
    <div
        data-slot="description-list-item"
        :class="[
            'grid grid-cols-1 gap-1 py-4',
            {
                '@xs/description-list:grid-cols-[1fr_2fr]': !stacked,
            },
        ]"
    >
        <dt class="text-sm leading-snug font-medium text-foreground">
            <slot name="title">
                {{ title }}
            </slot>
        </dt>

        <dd class="text-sm leading-snug text-muted-foreground">
            <slot />
        </dd>
    </div>
</template>
