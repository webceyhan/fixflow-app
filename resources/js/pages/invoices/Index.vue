<script setup lang="ts">
import {
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import InvoiceTable from '@/components/invoices/InvoiceTable.vue';
import Pagination from '@/components/Pagination.vue';
import { INVOICE_STATUS } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/invoices';
import type { Invoice, InvoiceStatus, NavItem, Paginated } from '@/types';

defineProps<{
    invoices: Paginated<Invoice>;
    filter?: {
        search?: string;
        status?: InvoiceStatus;
        created_at?: string;
    };
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
            <FilterGroup>
                <SearchFilter
                    placeholder="Search invoices ..."
                    :value="filter?.search"
                />

                <MultiSelectFilter
                    name="status"
                    title="Status"
                    :options="toOptions(INVOICE_STATUS)"
                    :value="filter?.status"
                />

                <RelativeDateFilter
                    name="created_at"
                    title="Created"
                    :value="filter?.created_at"
                />
            </FilterGroup>

            <InvoiceTable :invoices="invoices.data" />

            <Pagination v-bind="invoices" />
        </IndexLayout>
    </AppLayout>
</template>
