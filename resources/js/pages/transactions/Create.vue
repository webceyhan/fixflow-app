<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TransactionForm from '@/components/transactions/TransactionForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, show } from '@/routes/invoices';
import type { Invoice, NavItem, Transaction } from '@/types';

const props = defineProps<{
    transaction: Transaction;
    invoice: Invoice;
}>();

const title = 'Create Transaction';

const breadcrumbs: NavItem[] = [
    {
        title: 'Invoices',
        href: index(),
    },
    {
        title: toUUID(props.invoice.id, 'INV'),
        href: show(props.invoice),
    },
    {
        title,
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title />

        <EditLayout>
            <TransactionForm :transaction />
        </EditLayout>
    </AppLayout>
</template>
