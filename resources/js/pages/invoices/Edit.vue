<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Invoice } from '@/types';

import Heading from '@/components/Heading.vue';
import InvoiceForm from '@/components/invoices/InvoiceForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';

const props = defineProps<{
    invoice: Invoice;
}>();

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
            title: 'Edit',
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Edit Invoice" />

    <EditLayout>
        <InvoiceForm :invoice />
    </EditLayout>
</template>
