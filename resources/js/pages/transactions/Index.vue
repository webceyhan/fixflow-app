<script setup lang="ts">
import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TransactionTable from '@/components/transactions/TransactionTable.vue';
import { TRANSACTION_METHOD, TRANSACTION_TYPE } from '@/constants';
import AppLayout from '@/layouts/AppLayout.vue';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/transactions';
import type { NavItem, Paginated, Transaction } from '@/types';

defineProps<{
    transactions: Paginated<Transaction>;
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
            <FilterContainer>
                <SearchFilter placeholder="Search transactions..." />

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

                <DatePresetFilter name="created_at" />
            </FilterContainer>

            <TransactionTable :transactions="transactions.data" />

            <Pagination v-bind="transactions" />
        </IndexLayout>
    </AppLayout>
</template>
