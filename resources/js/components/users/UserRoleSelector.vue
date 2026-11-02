<script setup lang="ts">
import type { Option, UserRole } from '@/types';

import { FormSelector } from '@/components/form';
import { USER_ROLE, USER_ROLE_ICON_MAP } from '@/constants';
import { toOptions } from '@/lib/utils';

const options: Option<UserRole>[] = toOptions(USER_ROLE, USER_ROLE_ICON_MAP);

const model = defineModel<UserRole>();
</script>

<template>
    <FormSelector :options v-model="model" />
</template>
