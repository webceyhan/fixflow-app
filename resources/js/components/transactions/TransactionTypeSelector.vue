<script setup lang="ts">
import { FormSelector } from '@/components/form';
import { TRANSACTION_TYPE, TRANSACTION_TYPE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';
import type { Option, TransactionType } from '@/types';

const options: Option<TransactionType>[] = toOptions(
    TRANSACTION_TYPE,
    TRANSACTION_TYPE_ICON_MAP,
);

const model = defineModel<TransactionType>();
</script>

<template>
    <FormSelector class="lg:grid lg:grid-cols-3" :options v-model="model" />
</template>
