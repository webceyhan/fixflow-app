<script setup lang="ts">
import { setLayoutProps } from '@inertiajs/vue3';

import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import OrderCard from '@/components/orders/OrderCard.vue';
import OrderToolbar from '@/components/orders/OrderToolbar.vue';
import Placeholder from '@/components/Placeholder.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { index, update } from '@/routes/orders';
import type { Order } from '@/types';

const props = defineProps<{
    order: Order;
}>();

setLayoutProps({
    breadcrumbs: [
        {
            title: 'Orders',
            href: index(),
        },
        {
            title: props.order.ref,
            href: '',
        },
    ],
});
</script>

<template>
    <Heading title="Order Details">
        <OrderToolbar :order />
    </Heading>

    <ShowLayout>
        <OrderCard :order />

        <NoteCard :data="order" :href="update(order)" />

        <Placeholder class="col-start-2 row-span-2 row-start-1 max-lg:hidden" />
    </ShowLayout>
</template>
