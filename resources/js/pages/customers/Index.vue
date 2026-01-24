<script setup lang="ts">
import { CreateAction } from '@/components/action';
import CustomerTable from '@/components/customers/CustomerTable.vue';
import {
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import { CUSTOMER_STATUS, CUSTOMER_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { create, index } from '@/routes/customers';
import type {
    Customer,
    CustomerStatus,
    CustomerType,
    NavItem,
    Paginated,
} from '@/types';

defineProps<{
    customers: Paginated<Customer>;
    filter?: {
        search?: string;
        type?: CustomerType;
        status?: CustomerStatus;
    };
    isAdmin?: boolean;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Customers',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Customers">
            <CreateAction v-if="isAdmin" :href="create()" />
        </Heading>

        <IndexLayout>
            <FilterGroup>
                <SearchFilter
                    placeholder="Search customers ..."
                    :value="filter?.search"
                />

                <MultiSelectFilter
                    name="type"
                    title="Type"
                    :options="toOptions(CUSTOMER_TYPE)"
                />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(CUSTOMER_STATUS)"
                />

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <CustomerTable :customers="customers.data" />

            <Pagination v-bind="customers" />
        </IndexLayout>
    </AppLayout>
</template>
