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
import { useToggleFilter } from '@/composables/useFilter';
import { useOptions } from '@/composables/useOptions';
import type { Icon, Option } from '@/types';

interface Props {
    name: string;
    title: string;
    icon?: Icon;
    options: Option[];
    highlightOnHover?: boolean;
}

const props = defineProps<Props>();

const { state, dirty, toggle, clear } = useToggleFilter({
    name: props.name,
});

const { facades, filteredBy } = useOptions({
    state,
    initialOptions: props.options,
    checkedPredicate: (value, state) => state?.includes(value) ?? false,
});

const pinnedOptions = filteredBy(({ pinned }) => !!pinned);
const unpinnedOptions = filteredBy(({ pinned }) => !pinned);
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <FilterTrigger :icon :title :facades />
        </PopoverTrigger>

        <PopoverContent class="w-50 p-0" align="start">
            <Filter
                :searchable="options.length > 5"
                :placeholder="title"
                :highlight-on-hover
            >
                <!-- pinned options -->
                <FilterGroup v-if="pinnedOptions?.length" sticky-top>
                    <FilterItem
                        v-for="option in pinnedOptions"
                        @select="toggle(option.value)"
                        v-bind="option"
                        with-checkbox
                    />
                </FilterGroup>

                <!-- options -->
                <FilterGroup>
                    <FilterItem
                        v-for="option in unpinnedOptions"
                        @select="toggle(option.value)"
                        v-bind="option"
                        with-checkbox
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
