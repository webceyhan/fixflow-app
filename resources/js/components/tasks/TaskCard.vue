<script setup lang="ts">
import { Currency, DateTime, Priority } from '@/components/data';
import TicketLink from '@/components/tickets/TicketLink.vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    DescriptionList,
    DescriptionListItem,
} from '@/components/ui/description-list';
import UserLink from '@/components/users/UserLink.vue';
import type { Task } from '@/types';

import TaskStatusBadge from './TaskStatusBadge.vue';

interface Props {
    task: Task;
}

defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Overview</CardTitle>
        </CardHeader>

        <CardContent>
            <DescriptionList>
                <DescriptionListItem title="Type">
                    <span class="capitalize">{{ task.type }}</span>
                </DescriptionListItem>

                <DescriptionListItem v-if="task.ticket" title="Ticket">
                    <TicketLink :ticket="task.ticket" />
                </DescriptionListItem>

                <DescriptionListItem title="Assignee">
                    <UserLink :user="task.assignee" />
                </DescriptionListItem>

                <DescriptionListItem title="Cost">
                    <Currency :value="task.cost" :off="!task.is_billable" />
                </DescriptionListItem>

                <DescriptionListItem title="Priority">
                    <Priority :value="task.priority" />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <TaskStatusBadge :value="task.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Approval">
                    <DateTime :value="task.approved_at" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="task.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="task.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
