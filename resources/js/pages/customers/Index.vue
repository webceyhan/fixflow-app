<script setup lang="ts">
import { CreateAction } from '@/components/action';
import CustomerTable from '@/components/customers/CustomerTable.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import { CUSTOMER_STATUS, CUSTOMER_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { create, index } from '@/routes/customers';
import type { Customer, NavItem, Paginated } from '@/types';

defineProps<{
    customers: Paginated<Customer>;
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
            <FilterContainer>
                <SearchFilter placeholder="Search customers..." />

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

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <CustomerTable :customers="customers.data" />

            <Pagination v-bind="customers" />
        </IndexLayout>
    </AppLayout>
</template>
