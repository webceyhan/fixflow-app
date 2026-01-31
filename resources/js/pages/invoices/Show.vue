<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InvoiceCard from '@/components/invoices/InvoiceCard.vue';
import InvoiceToolbar from '@/components/invoices/InvoiceToolbar.vue';
import NoteCard from '@/components/NoteCard.vue';
import Placeholder from '@/components/Placeholder.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, update } from '@/routes/invoices';
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

            <Placeholder
                class="col-start-2 row-span-2 row-start-1 max-lg:hidden"
            />
        </ShowLayout>
    </AppLayout>
</template>
