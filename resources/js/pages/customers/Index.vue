<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import { CreateAction } from '@/components/action';
import CustomerTable from '@/components/customers/CustomerTable.vue';
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import { CUSTOMER_STATUS, CUSTOMER_TYPE } from '@/constants';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { create, index } from '@/routes/customers';
import type { Customer, Paginated } from '@/types';

defineProps<{
    customers: Paginated<Customer>;
    isAdmin?: boolean;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Customers',
            href: index(),
        },
    ],
});
</script>

<template>
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
</template>
