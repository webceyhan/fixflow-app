<script setup lang="ts">
import { FormSelector } from '@/components/form';
import { CUSTOMER_TYPE, CUSTOMER_TYPE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';
import type { CustomerType, Option } from '@/types';

const options: Option<CustomerType>[] = toOptions(
    CUSTOMER_TYPE,
    CUSTOMER_TYPE_ICON_MAP,
);

const model = defineModel<CustomerType>();
</script>

<template>
    <FormSelector :options v-model="model" />
</template>
