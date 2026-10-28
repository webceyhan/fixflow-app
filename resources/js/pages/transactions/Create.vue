<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import TransactionForm from '@/components/transactions/TransactionForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';
import type { Invoice, Transaction } from '@/types';

const props = defineProps<{
    transaction: Transaction;
    invoice: Invoice;
}>();

const title = 'Create Transaction';

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Invoices',
            href: index(),
        },
        {
            title: props.invoice.ref,
            href: show(props.invoice),
        },
        {
            title,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading :title />

    <EditLayout>
        <TransactionForm :transaction />
    </EditLayout>
</template>
