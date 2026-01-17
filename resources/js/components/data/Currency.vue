<script setup lang="ts">
interface Props {
    value?: number;
    off?: boolean;
}

defineProps<Props>();

const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});
</script>

<template>
    <code :class="{ 'line-through': off }">
        {{ value ? formatter.format(value) : 0 }}
    </code>
</template>
