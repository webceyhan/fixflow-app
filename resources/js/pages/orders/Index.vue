<script setup lang="ts">
import AssigneeFilter from '@/components/filters/AssigneeFilter.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import OrderTable from '@/components/orders/OrderTable.vue';
import Pagination from '@/components/Pagination.vue';
import { ORDER_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/orders';
import type { NavItem, Order, Paginated } from '@/types';

defineProps<{
    orders: Paginated<Order>;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Orders',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Orders" />

        <IndexLayout>
            <FilterContainer>
                <SearchFilter placeholder="Search orders..." />

                <AssigneeFilter />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(ORDER_STATUS)"
                />

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <OrderTable :orders="orders.data" />

            <Pagination v-bind="orders" />
        </IndexLayout>
    </AppLayout>
</template>
