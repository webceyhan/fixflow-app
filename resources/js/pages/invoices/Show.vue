<script setup lang="ts">
import { CreateAction } from '@/components/action';
import Heading from '@/components/Heading.vue';
import InvoiceCard from '@/components/invoices/InvoiceCard.vue';
import InvoiceToolbar from '@/components/invoices/InvoiceToolbar.vue';
import NoteCard from '@/components/NoteCard.vue';
import TransactionList from '@/components/transactions/TransactionList.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, update } from '@/routes/invoices';
import { create as createTransaction } from '@/routes/transactions';
import type { Invoice, NavItem } from '@/types';

const props = defineProps<{
    invoice: Invoice;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Invoices',
        href: index(),
    },
    {
        title: toUUID(props.invoice.id, 'INV'),
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Invoice Details">
            <InvoiceToolbar :invoice />
        </Heading>

        <ShowLayout>
            <InvoiceCard :invoice />

            <NoteCard :data="invoice" :href="update(invoice)" />

            <div class="row-span-2 space-y-4 lg:col-start-2 lg:row-start-1">
                <section class="space-y-2">
                    <h3>Transactions</h3>

                    <TransactionList :transactions="invoice.transactions" />

                    <CreateAction
                        class="w-full"
                        variant="ghost"
                        size="lg"
                        :href="createTransaction(invoice)"
                    >
                        Add new transaction
                    </CreateAction>
                </section>
            </div>
        </ShowLayout>
    </AppLayout>
</template>
