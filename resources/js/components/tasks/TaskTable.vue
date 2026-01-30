<script setup lang="ts">
import { Currency, DateTime, Priority, Tick } from '@/components/data';
import TicketLink from '@/components/tickets/TicketLink.vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
    TableSortLink,
} from '@/components/ui/table';
import UserLink from '@/components/users/UserLink.vue';
import { TaskTypeIcon } from '@/icons';
import type { Task } from '@/types';
import DeviceLink from '../devices/DeviceLink.vue';
import TaskLink from './TaskLink.vue';
import TaskStatusBadge from './TaskStatusBadge.vue';

interface Props {
    tasks: Task[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="type"> Task </TableSortLink>
                </TableHead>

                <TableHead> Ticket </TableHead>

                <TableHead> Assignee </TableHead>

                <TableHead> Priority </TableHead>

                <TableHead>
                    <TableSortLink name="cost"> Cost </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="approved_at"> Approved </TableSortLink>
                </TableHead>

                <TableHead> Status </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="task in tasks" :key="task.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <TaskTypeIcon :value="task.type" />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <TaskLink class="font-medium capitalize" :task />
                    <p class="truncate text-muted-foreground">
                        {{ task.note }}
                    </p>
                </TableCell>

                <TableCell>
                    <TicketLink :ticket="task.ticket!" />
                    <DeviceLink
                        class="block text-muted-foreground"
                        :device="task.ticket?.device!"
                    />
                </TableCell>

                <TableCell>
                    <UserLink :user="task.assignee" />
                </TableCell>

                <TableCell>
                    <Priority :value="task.priority" />
                </TableCell>

                <TableCell>
                    <Currency :value="task.cost" :off="!task.is_billable" />
                </TableCell>

                <TableCell>
                    <Tick :value="task.approved_at" />
                </TableCell>

                <TableCell>
                    <TaskStatusBadge :value="task.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="task.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
