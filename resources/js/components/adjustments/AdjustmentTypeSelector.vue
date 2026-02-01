<script setup lang="ts">
import { FormSelector } from '@/components/form';
import { ADJUSTMENT_TYPE, ADJUSTMENT_TYPE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';
import type { AdjustmentType, Option } from '@/types';

const options: Option<AdjustmentType>[] = toOptions(
    ADJUSTMENT_TYPE,
    ADJUSTMENT_TYPE_ICON_MAP,
);

const model = defineModel<AdjustmentType>();
</script>

<template>
    <FormSelector class="lg:grid lg:grid-cols-2" :options v-model="model" />
</template>
