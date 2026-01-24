<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/customers';
import type { Customer } from '@/types';

interface Props {
    customer: Customer;
}

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(customer)">
        {{ customer.name }}
    </TextLink>
</template>
