<script setup lang="ts">
import type { Transaction } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/transactions';

type Props = {
    transaction: Transaction;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(transaction)">
        {{ transaction.ref }}
    </TextLink>
</template>
