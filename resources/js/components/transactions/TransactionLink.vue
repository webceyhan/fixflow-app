<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { toUUID } from '@/lib/utils';
import { show } from '@/routes/transactions';
import type { Transaction } from '@/types';

interface Props {
    transaction: Transaction;
}

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(transaction)">
        {{ toUUID(transaction.id, 'TXN') }}
    </TextLink>
</template>
