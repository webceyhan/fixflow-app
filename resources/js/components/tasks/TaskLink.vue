<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { show } from '@/routes/tasks';
import type { Task } from '@/types';

interface Props {
    task: Task;
}

const props = defineProps<Props>();
</script>

<template>
    <TextLink :href="show(task)">
        {{ task.type }}
    </TextLink>
</template>
