<script setup lang="ts">
import type { UserRole } from '@/types';

interface Props {
    value: UserRole;
}

defineProps<Props>();
</script>

<template>
    <span class="capitalize">
        {{ value }}
    </span>
</template>
