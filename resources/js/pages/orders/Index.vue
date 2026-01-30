<script setup lang="ts">
import {
    AssigneeFilter,
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import OrderTable from '@/components/orders/OrderTable.vue';
import Pagination from '@/components/Pagination.vue';
import { ORDER_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/orders';
import type { NavItem, Order, OrderStatus, Paginated } from '@/types';

defineProps<{
    orders: Paginated<Order>;
    filter?: {
        search?: string;
        assignee?: string;
        status?: OrderStatus;
        created_at?: string;
    };
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
            <FilterGroup>
                <SearchFilter
                    placeholder="Search orders ..."
                    :value="filter?.search"
                />

                <AssigneeFilter />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(ORDER_STATUS)"
                />

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <OrderTable :orders="orders.data" />

            <Pagination v-bind="orders" />
        </IndexLayout>
    </AppLayout>
</template>
