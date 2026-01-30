<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import OrderForm from '@/components/orders/OrderForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index } from '@/routes/orders';
import { show as ticketShow } from '@/routes/tickets';
import type { NavItem, Order, Ticket } from '@/types';

const props = defineProps<{
    order: Order;
    ticket: Ticket;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Orders',
        href: index(),
    },
    {
        title: props.ticket.title,
        href: ticketShow(props.ticket),
    },
    {
        title: 'Create',
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Create Order" />

        <EditLayout>
            <OrderForm :order />
        </EditLayout>
    </AppLayout>
</template>
