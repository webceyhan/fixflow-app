<script setup lang="ts">
import { CommandItem } from '@/components/ui/command';

defineEmits<{
    (e: 'click'): void;
}>();
</script>

<template>
    <CommandItem
        class="justify-center bg-muted/50 text-center"
        @select.prevent="$emit('click')"
        :value="null"
    >
        <slot> Clear filters</slot>
    </CommandItem>
</template>
