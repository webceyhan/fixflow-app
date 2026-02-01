<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AdjustmentForm from '@/components/adjustments/AdjustmentForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { toUUID } from '@/lib/utils';
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
            <AdjustmentForm :adjustment />
        </EditLayout>
    </AppLayout>
</template>
