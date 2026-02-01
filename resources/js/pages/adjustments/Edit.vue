<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import AdjustmentForm from '@/components/adjustments/AdjustmentForm.vue';
import AdjustmentToolbar from '@/components/adjustments/AdjustmentToolbar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, show } from '@/routes/invoices';
import type { Adjustment, NavItem } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
    adjustment: Adjustment;
}>();

const title = `Edit Adjustment ${toUUID(props.adjustment.id, 'ADJ')}`;

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Invoices',
        href: index(),
    },
    {
        title: toUUID(props.adjustment.invoice_id, 'INV'),
        href: show(props.adjustment.invoice_id),
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
            <AdjustmentToolbar :adjustment />
        </Heading>

        <EditLayout>
            <AdjustmentForm :adjustment />
        </EditLayout>
    </AppLayout>
</template>
