<script setup lang="ts">
import { computed } from 'vue';

import type { NavItem, Transaction } from '@/types';

import Heading from '@/components/Heading.vue';
import TransactionForm from '@/components/transactions/TransactionForm.vue';
import TransactionToolbar from '@/components/transactions/TransactionToolbar.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';

const props = defineProps<{
    transaction: Transaction;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Invoices',
        href: index(),
    },
    {
        title: props.transaction.invoice!.ref,
        href: show(props.transaction.invoice_id),
    },
    {
        title: props.transaction.ref,
        href: '',
    },
]);
</script>

<template>
    <Heading title="Edit Transaction">
        <TransactionToolbar :transaction />
    </Heading>

    <EditLayout>
        <TransactionForm :transaction />
    </EditLayout>
</template>
