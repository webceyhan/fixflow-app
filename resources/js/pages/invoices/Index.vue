<script setup lang="ts">
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import InvoiceTable from '@/components/invoices/InvoiceTable.vue';
import Pagination from '@/components/Pagination.vue';
import { INVOICE_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/invoices';
import type { Invoice, NavItem, Paginated } from '@/types';

defineProps<{
    invoices: Paginated<Invoice>;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Invoices',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Invoices" />

        <IndexLayout>
            <FilterContainer>
                <SearchFilter placeholder="Search invoices..." />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(INVOICE_STATUS)"
                />

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <InvoiceTable :invoices="invoices.data" />

            <Pagination v-bind="invoices" />
        </IndexLayout>
    </AppLayout>
</template>
