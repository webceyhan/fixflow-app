<script setup lang="ts">
import {
    FilterGroup,
    MultiSelectFilter,
    RelativeDateFilter,
    SearchFilter,
} from '@/components/filter';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TransactionTable from '@/components/transactions/TransactionTable.vue';
import { TRANSACTION_METHOD, TRANSACTION_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/transactions';
import type {
    NavItem,
    Paginated,
    Transaction,
    TransactionMethod,
    TransactionType,
} from '@/types';

defineProps<{
    transactions: Paginated<Transaction>;
    filter?: {
        search?: string;
        method?: TransactionMethod;
        type?: TransactionType;
        created_at?: string;
    };
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Transactions',
        href: index(),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Transactions" />

        <IndexLayout>
            <FilterGroup>
                <SearchFilter
                    placeholder="Search transactions ..."
                    :value="filter?.search"
                />

                <MultiSelectFilter
                    name="method"
                    title="Method"
                    :options="toOptions(TRANSACTION_METHOD)"
                />

                <MultiSelectFilter
                    name="type"
                    title="Type"
                    :options="toOptions(TRANSACTION_TYPE)"
                />

                <RelativeDateFilter name="created_at" />
            </FilterGroup>

            <TransactionTable :transactions="transactions.data" />

            <Pagination v-bind="transactions" />
        </IndexLayout>
    </AppLayout>
</template>
