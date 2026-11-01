<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import type { Order, Ticket } from '@/types';

import Heading from '@/components/Heading.vue';
import OrderForm from '@/components/orders/OrderForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';

const props = defineProps<{
    order: Order;
    ticket: Ticket;
}>();

const title = 'Create Order';

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Tickets',
            href: index(),
        },
        {
            title: props.ticket.ref,
            href: show(props.ticket),
        },
        {
            title,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading :title />

    <EditLayout>
        <OrderForm :order />
    </EditLayout>
</template>
