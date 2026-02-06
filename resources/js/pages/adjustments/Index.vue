<script setup lang="ts">
import AdjustmentTable from '@/components/adjustments/AdjustmentTable.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import { ADJUSTMENT_REASON, ADJUSTMENT_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/adjustments';
import type { Adjustment, NavItem, Paginated } from '@/types';

defineProps<{
    adjustments: Paginated<Adjustment>;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Adjustments',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Adjustments" />

        <IndexLayout>
            <FilterContainer>
                <SearchFilter placeholder="Search adjustments..." />

                <MultiSelectFilter
                    name="reason"
                    title="Reason"
                    :options="toOptions(ADJUSTMENT_REASON)"
                />

                <MultiSelectFilter
                    name="type"
                    title="Type"
                    :options="toOptions(ADJUSTMENT_TYPE)"
                />

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <AdjustmentTable :adjustments="adjustments.data" />

            <Pagination v-bind="adjustments" />
        </IndexLayout>
    </AppLayout>
</template>
