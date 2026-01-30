<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import NoteCard from '@/components/NoteCard.vue';
import OrderCard from '@/components/orders/OrderCard.vue';
import OrderToolbar from '@/components/orders/OrderToolbar.vue';
import Placeholder from '@/components/Placeholder.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import ShowLayout from '@/layouts/ShowLayout.vue';
import { toUUID } from '@/lib/utils';
import { index, update } from '@/routes/orders';
import type { NavItem, Order } from '@/types';

const props = defineProps<{
    order: Order;
}>();

const breadcrumbs: NavItem[] = [
    {
        title: 'Orders',
        href: index(),
    },
    {
        title: toUUID(props.order.id, 'ORD'),
        href: '',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Heading title="Order Details">
            <OrderToolbar :order />
        </Heading>

        <ShowLayout>
            <OrderCard :order />

            <NoteCard :data="order" :href="update(order)" />

            <Placeholder
                class="col-start-2 row-span-2 row-start-1 max-lg:hidden"
            />
        </ShowLayout>
    </AppLayout>
</template>
