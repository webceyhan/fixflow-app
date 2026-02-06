<script setup lang="ts">
import { CommandItem } from '@/components/ui/command';
import type { Icon } from '@/types';
import { CheckIcon } from 'lucide-vue-next';
import { AcceptableValue } from 'reka-ui';
import { Checkbox } from '../checkbox';

interface Props {
    value: AcceptableValue;
    label: string;
    class?: string;
    icon?: Icon;
    checked?: boolean;
    pinned?: boolean;
    withCheckbox?: boolean;
}

defineProps<Props>();

const emits = defineEmits<{
    select: [value: AcceptableValue];
}>();
</script>

<template>
    <CommandItem :value :class @select="emits('select', value)">
        <!-- checkbox -->
        <Checkbox
            v-if="withCheckbox"
            tabindex="-1"
            class="mr-2 border-primary/25"
            :model-value="checked"
        />

        <!-- icon -->
        <component
            v-if="icon"
            :is="icon"
            :class="pinned ? 'text-info' : 'text-muted-foreground'"
        />

        <!-- label -->
        <span>{{ label }}</span>

        <!-- check icon -->
        <CheckIcon v-if="!withCheckbox && checked" class="ml-auto" />
    </CommandItem>
</template>
