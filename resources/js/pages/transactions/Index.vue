<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Paginated, Transaction } from '@/types';

import DatePresetFilter from '@/components/filters/DatePresetFilter.vue';
import FilterContainer from '@/components/filters/FilterContainer.vue';
import MultiSelectFilter from '@/components/filters/MultiSelectFilter.vue';
import SearchFilter from '@/components/filters/SearchFilter.vue';
import Heading from '@/components/Heading.vue';
import Pagination from '@/components/Pagination.vue';
import TransactionTable from '@/components/transactions/TransactionTable.vue';
import { TRANSACTION_METHOD, TRANSACTION_TYPE } from '@/constants';
import IndexLayout from '@/layouts/IndexLayout.vue';
import { toOptions } from '@/lib/utils';
import { index } from '@/routes/transactions';

defineProps<{
    transactions: Paginated<Transaction>;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Transactions',
            href: index(),
        },
    ],
});
</script>

<template>
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
</template>
