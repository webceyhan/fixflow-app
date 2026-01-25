<script setup lang="ts">
import { FormSelector } from '@/components/form';
import { DEVICE_TYPE, DEVICE_TYPE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';
import type { DeviceType, Option } from '@/types';

const options: Option<DeviceType>[] = toOptions(
    DEVICE_TYPE,
    DEVICE_TYPE_ICON_MAP,
);

const model = defineModel<DeviceType>();
</script>

<template>
    <FormSelector class="lg:grid lg:grid-cols-3" :options v-model="model" />
</template>
