<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import type { StateColor } from '@/types';

/**
 * Dynamic classes
 *
 * text-default border-default/50
 * text-info border-info/50
 * text-success border-success/50
 * text-warning border-warning/50
 * text-destructive border-destructive/50
 * text-neutral border-neutral/50
 */

interface Props {
    color: StateColor;
}

defineProps<Props>();
</script>

<template>
    <Badge variant="outline" :class="`text-${color} border-${color}/50`">
        <slot />
    </Badge>
</template>
