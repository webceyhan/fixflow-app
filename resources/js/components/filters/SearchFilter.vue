<script setup lang="ts">
import { useDebounceFn } from '@vueuse/core';
import { SearchIcon } from 'lucide-vue-next';

import {
    InputGroup,
    InputGroupAddon,
    InputGroupInput,
} from '@/components/ui/input-group';
import { useFilter } from '@/composables/useFilter';

interface Props {
    placeholder?: string;
}

defineProps<Props>();

const { state, update } = useFilter<string>({ name: 'search' });

const search = useDebounceFn(update, 200);
</script>

<template>
    <InputGroup class="md:max-w-2xs">
        <InputGroupInput
            :placeholder="placeholder || 'Search...'"
            :model-value="state"
            @update:model-value="search"
        />
        <InputGroupAddon>
            <SearchIcon />
        </InputGroupAddon>
    </InputGroup>
</template>
