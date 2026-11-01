<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Adjustment, Invoice } from '@/types';

import AdjustmentForm from '@/components/adjustments/AdjustmentForm.vue';
import Heading from '@/components/Heading.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';

const props = defineProps<{
    adjustment: Adjustment;
    invoice: Invoice;
}>();

const title = 'Create Adjustment';

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
    <Heading :title="title" />

    <EditLayout>
        <AdjustmentForm :adjustment />
    </EditLayout>
</template>
