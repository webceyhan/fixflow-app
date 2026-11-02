<script setup lang="ts">
import type { Order } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/orders';

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
