<script setup lang="ts">
import { Alert, AlertTitle } from '@/components/ui/alert';
import { AlertCircleIcon } from 'lucide-vue-next';

interface Props {
    title: string;
}

defineProps<Props>();
</script>

<template>
    <Alert class="text-success">
        <AlertCircleIcon class="size-4" />
        <AlertTitle>{{ title }}</AlertTitle>
    </Alert>
</template>
