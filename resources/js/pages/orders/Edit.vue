<script setup lang="ts">
import { computed } from 'vue';

import type { NavItem, Order } from '@/types';

import Heading from '@/components/Heading.vue';
import OrderForm from '@/components/orders/OrderForm.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/orders';

const props = defineProps<{
    order: Order;
}>();

const breadcrumbs = computed<NavItem[]>(() => [
    {
        title: 'Orders',
        href: index(),
    },
    {
        title: props.order.ref,
        href: show(props.order),
    },
    {
        title: 'Edit',
        href: '',
    },
]);
</script>

<template>
    <Heading title="Edit Order" />

    <EditLayout>
        <OrderForm :order />
    </EditLayout>
</template>
