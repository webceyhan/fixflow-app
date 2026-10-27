<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/customers';
import type { Customer } from '@/types';

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
