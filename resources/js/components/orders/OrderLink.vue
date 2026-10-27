<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/orders';
import type { Order } from '@/types';

type Props = {
    order: Order;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(order)">
        {{ order.name }}
    </TextLink>
</template>
