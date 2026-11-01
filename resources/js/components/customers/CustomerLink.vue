<script setup lang="ts">
import type { Customer } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/customers';

type Props = {
    customer: Customer;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(customer)">
        {{ customer.name }}
    </TextLink>
</template>
