<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
</script>

<template>
    <Badge class="rounded-sm px-1 font-normal" variant="secondary">
        <slot />
    </Badge>
</template>
