<script setup lang="ts">
import { computed } from 'vue';

import Heading from '@/components/Heading.vue';
import InvoiceForm from '@/components/invoices/InvoiceForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';
import type { Invoice, NavItem } from '@/types';

const props = defineProps<{
    invoice: Invoice;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
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
]);
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Edit Invoice" />

        <EditLayout>
            <InvoiceForm :invoice />
        </EditLayout>
    </AppLayout>
</template>
