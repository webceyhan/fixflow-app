<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import OrderForm from '@/components/orders/OrderForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/tickets';
import type { NavItem, Order, Ticket } from '@/types';

const props = defineProps<{
    order: Order;
    ticket: Ticket;
}>();

const title = 'Create Order';

const breadcrumbs: NavItem[] = [
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
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading :title />

        <EditLayout>
            <OrderForm :order />
        </EditLayout>
    </AppLayout>
</template>
