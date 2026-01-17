<script setup lang="ts">
interface Props {
    value?: number;
    off?: boolean;
}

defineProps<Props>();

const formatter = new Intl.NumberFormat('en-US', {
    style: 'percent',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});
</script>

<template>
    <code :class="{ 'line-through': off }">
        {{ value ? formatter.format(value / 100) : 0 }}
    </code>
</template>
