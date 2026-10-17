<script setup lang="ts">
import AdjustmentForm from '@/components/adjustments/AdjustmentForm.vue';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';
import type { Adjustment, Invoice, NavItem } from '@/types';

const props = defineProps<{
    adjustment: Adjustment;
    invoice: Invoice;
}>();

const title = 'Create Adjustment';

const breadcrumbs: NavItem[] = [
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
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title />

        <EditLayout>
            <AdjustmentForm :adjustment />
        </EditLayout>
    </AppLayout>
</template>
