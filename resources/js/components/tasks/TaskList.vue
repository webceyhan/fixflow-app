<script setup lang="ts">
import { Currency, DateTime, Priority, Tick } from '@/components/data';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    Empty,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemGroup,
    ItemMedia,
    ItemTitle,
} from '@/components/ui/item';
import { TaskIcon, TaskTypeIcon } from '@/icons';
import { show } from '@/routes/tasks';
import type { Task } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import TaskStatusBadge from './TaskStatusBadge.vue';

interface Props {
    tasks?: Task[];
}

defineProps<Props>();
</script>

<template>
    <Empty v-if="!tasks?.length">
        <EmptyHeader>
            <EmptyMedia>
                <TaskIcon class="size-12" />
            </EmptyMedia>
            <EmptyTitle>No tasks yet</EmptyTitle>
            <EmptyDescription>
                You haven't created any tasks yet. Get started by adding your
                first task for this ticket.
            </EmptyDescription>
        </EmptyHeader>
    </Empty>

    <ItemGroup v-else class="gap-2">
        <Item
            v-for="task in tasks"
            :key="task.id"
            variant="outline"
            :as="Link"
            :href="show(task)"
        >
            <ItemMedia>
                <Avatar class="size-10">
                    <AvatarFallback>
                        <TaskTypeIcon :value="task.type" />
                    </AvatarFallback>
                </Avatar>
            </ItemMedia>

            <ItemContent>
                <ItemTitle class="capitalize">
                    {{ task.type }}
                </ItemTitle>

                <ItemDescription v-if="task.note">
                    {{ task.note }}
                </ItemDescription>

                <ItemDescription class="flex items-center gap-4">
                    <!-- <UserLink :user="task.assignee" placeholder="Unassigned" /> -->

                    <Priority :value="task.priority" />

                    <Currency :value="task.cost" :off="!task.is_billable" />

                    <Tick :value="task.approved_at" pending> Approval </Tick>

                    <TaskStatusBadge :value="task.status" />

                    <DateTime :value="task.created_at" />
                </ItemDescription>
            </ItemContent>

            <ItemActions>
                <ChevronRight class="size-4 text-muted-foreground" />
            </ItemActions>
        </Item>
    </ItemGroup>
</template>
