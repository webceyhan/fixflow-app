<script setup lang="ts">
import { FormSelector } from '@/components/form';
import { USER_ROLE, USER_ROLE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';
import type { Option, UserRole } from '@/types';

const options: Option<UserRole>[] = toOptions(USER_ROLE, USER_ROLE_ICON_MAP);

const model = defineModel<UserRole>();
</script>

<template>
    <FormSelector :options v-model="model" />
</template>
