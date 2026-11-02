<script setup lang="ts">
import type { Task } from '@/types';

import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/tasks';

type Props = {
    task: Task;
};

defineProps<Props>();
</script>

<template>
    <TextLink :href="show(task)">
        {{ task.type }}
    </TextLink>
</template>
