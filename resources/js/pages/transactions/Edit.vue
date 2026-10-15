<script setup lang="ts">
import { computed } from 'vue';

import Heading from '@/components/Heading.vue';
import TransactionForm from '@/components/transactions/TransactionForm.vue';
import TransactionToolbar from '@/components/transactions/TransactionToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';
import type { NavItem, Transaction } from '@/types';

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
    <AppLayout :breadcrumbs>
        <Heading title="Edit Transaction">
            <TransactionToolbar :transaction />
        </Heading>

        <EditLayout>
            <TransactionForm :transaction />
        </EditLayout>
    </AppLayout>
</template>
