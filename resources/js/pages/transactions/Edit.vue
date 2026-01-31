<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import TransactionForm from '@/components/transactions/TransactionForm.vue';
import TransactionToolbar from '@/components/transactions/TransactionToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, show } from '@/routes/invoices';
import type { NavItem, Transaction } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    transaction: Transaction;
}>();

const title = `Edit Transaction ${toUUID(props.transaction.id, 'TXN')}`;

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Invoices',
        href: index(),
    },
    {
        title: toUUID(props.transaction.invoice_id, 'INV'),
        href: show(props.transaction.invoice_id),
    },
    {
        title,
        href: '',
    },
]);
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title>
            <TransactionToolbar :transaction />
        </Heading>

        <EditLayout>
            <TransactionForm :transaction />
        </EditLayout>
    </AppLayout>
</template>
