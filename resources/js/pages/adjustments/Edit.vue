<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import AdjustmentForm from '@/components/adjustments/AdjustmentForm.vue';
import AdjustmentToolbar from '@/components/adjustments/AdjustmentToolbar.vue';
import Heading from '@/components/Heading.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';
import type { Adjustment } from '@/types';

const props = defineProps<{
    adjustment: Adjustment;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Invoices',
            href: index(),
        },
        {
            title: props.adjustment.invoice!.ref,
            href: show(props.adjustment.invoice_id),
        },
        {
            title: props.adjustment.ref,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Edit Adjustment">
        <AdjustmentToolbar :adjustment />
    </Heading>

    <EditLayout>
        <AdjustmentForm :adjustment />
    </EditLayout>
</template>
