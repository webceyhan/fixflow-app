<script setup lang="ts">
import AdjustmentTable from '@/components/adjustments/AdjustmentTable.vue';
import {
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import { ADJUSTMENT_REASON, ADJUSTMENT_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/adjustments';
import type {
    Adjustment,
    AdjustmentReason,
    AdjustmentType,
    NavItem,
    Paginated,
} from '@/types';

defineProps<{
    adjustments: Paginated<Adjustment>;
    filter?: {
        search?: string;
        reason?: AdjustmentReason;
        type?: AdjustmentType;
        created_at?: string;
    };
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
            <FilterGroup>
                <SearchFilter
                    placeholder="Search adjustments ..."
                    :value="filter?.search"
                />

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

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <AdjustmentTable :adjustments="adjustments.data" />

            <Pagination v-bind="adjustments" />
        </IndexLayout>
    </AppLayout>
</template>
