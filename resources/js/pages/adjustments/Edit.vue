<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AdjustmentForm from '@/components/adjustments/AdjustmentForm.vue';
import AdjustmentToolbar from '@/components/adjustments/AdjustmentToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/invoices';
import type { Adjustment, NavItem } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    adjustment: Adjustment;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
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
]);
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Edit Adjustment">
            <AdjustmentToolbar :adjustment />
        </Heading>

        <EditLayout>
            <AdjustmentForm :adjustment />
        </EditLayout>
    </AppLayout>
</template>
