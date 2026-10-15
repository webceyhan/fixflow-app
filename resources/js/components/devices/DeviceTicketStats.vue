<script setup lang="ts">
import { computed } from 'vue';

import { Percent } from '@/components/data';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import type { Ticket } from '@/types';

interface Props {
    tickets?: Ticket[];
}

const props = defineProps<Props>();

const counts = computed(() => {
    const tickets = props.tickets ?? [];
    const pending = tickets.filter((ticket) =>
        ['created', 'in_progress', 'on_hold'].includes(ticket.status),
    ).length;
    const completed = tickets.filter((ticket) =>
        ['resolved', 'closed'].includes(ticket.status),
    ).length;
    const total = pending + completed;

    return { pending, completed, total };
});

const progress = computed(() => {
    if (counts.value.total === 0) {
        return 0;
    }

    return (counts.value.completed / counts.value.total) * 100;
});
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle class="text-sm font-medium">Ticket progress</CardTitle>
        </CardHeader>

        <CardContent class="space-y-2">
            <div class="text-2xl font-bold">
                <Percent :value="progress" />
            </div>

            <div class="bg-muted h-2 overflow-hidden rounded-full">
                <div
                    class="bg-primary h-full transition-all"
                    :style="{ width: `${progress}%` }"
                />
            </div>

            <p class="flex justify-between text-xs text-muted-foreground">
                <span>{{ counts.completed }} completed</span>
                <span>{{ counts.pending }} pending</span>
            </p>
        </CardContent>
    </Card>
</template>
