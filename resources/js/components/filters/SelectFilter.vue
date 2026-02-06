<script setup lang="ts">
import {
    Filter,
    FilterClear,
    FilterGroup,
    FilterItem,
    FilterTrigger,
} from '@/components/ui/filter';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { useFilter } from '@/composables/useFilter';
import { useOptions } from '@/composables/useOptions';
import type { Icon, Option } from '@/types';
import { ref } from 'vue';

interface Props {
    name: string;
    title: string;
    icon?: Icon;
    options: Option[];
    highlightOnHover?: boolean;
}

const props = defineProps<Props>();

const open = ref<boolean>(false);

const { state, dirty, update, clear } = useFilter({
    name: props.name,
    onChange: () => (open.value = false),
});

const { facades, filteredBy } = useOptions({
    state,
    initialOptions: props.options,
    checkedPredicate: (value, state) => value === state,
});

const pinnedOptions = filteredBy(({ pinned }) => !!pinned);
const unpinnedOptions = filteredBy(({ pinned }) => !pinned);
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <FilterTrigger :title :icon :facades />
        </PopoverTrigger>

        <PopoverContent class="w-56 p-0" align="start">
            <Filter
                :searchable="options.length > 5"
                :placeholder="title"
                :highlight-on-hover
            >
                <!-- pinned options -->
                <FilterGroup v-if="pinnedOptions?.length" sticky-top>
                    <FilterItem
                        v-for="option in pinnedOptions"
                        @select="update(option.value)"
                        v-bind="option"
                    />
                </FilterGroup>

                <!-- options -->
                <FilterGroup>
                    <FilterItem
                        v-for="option in unpinnedOptions"
                        @select="update(option.value)"
                        v-bind="option"
                    />
                </FilterGroup>

                <!-- clear action -->
                <FilterGroup v-if="dirty" sticky-bottom>
                    <FilterClear @click="clear" />
                </FilterGroup>
            </Filter>
        </PopoverContent>
    </Popover>
</template>
