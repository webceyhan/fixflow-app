<script setup lang="ts">
import { CommandGroup } from '@/components/ui/command';

interface Props {
    stickyTop?: boolean;
    stickyBottom?: boolean;
}

defineProps<Props>();
</script>

<template>
    <CommandGroup
        :class="{
            'sticky top-0 z-10 border-b bg-background': stickyTop,
            'sticky bottom-0 z-10 border-t bg-background': stickyBottom,
        }"
    >
        <slot />
    </CommandGroup>
</template>
