<script setup lang="ts">
import { computed } from 'vue';

import Heading from '@/components/Heading.vue';
import OrderForm from '@/components/orders/OrderForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import EditLayout from '@/layouts/EditLayout.vue';
import { index, show } from '@/routes/orders';
import type { NavItem, Order } from '@/types';

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
    <AppLayout :breadcrumbs>
        <Heading title="Edit Order" />

        <EditLayout>
            <OrderForm :order />
        </EditLayout>
    </AppLayout>
</template>
