<script setup lang="ts">
import type { Option, TaskType } from '@/types';

import { FormSelector } from '@/components/form';
import { TASK_TYPE, TASK_TYPE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';

const options: Option<TaskType>[] = toOptions(TASK_TYPE, TASK_TYPE_ICON_MAP);

const model = defineModel<TaskType>();
</script>

<template>
    <FormSelector class="lg:grid lg:grid-cols-3" :options v-model="model" />
</template>
