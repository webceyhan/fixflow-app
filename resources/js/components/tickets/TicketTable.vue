<script setup lang="ts">
import CustomerLink from '@/components/customers/CustomerLink.vue';
import { DateTime, Priority } from '@/components/data';
import DeviceLink from '@/components/devices/DeviceLink.vue';
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
import { TicketIcon } from '@/icons';
import type { Ticket } from '@/types';
import TicketLink from './TicketLink.vue';
import TicketStatusBadge from './TicketStatusBadge.vue';

interface Props {
    tickets: Ticket[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="title"> Ticket </TableSortLink>
                </TableHead>

                <TableHead> Customer </TableHead>

                <TableHead> Assignee </TableHead>

                <TableHead> Priority </TableHead>

                <TableHead>
                    <TableSortLink name="due_date"> Due Date </TableSortLink>
                </TableHead>

                <TableHead> Status </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="ticket in tickets" :key="ticket.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <TicketIcon />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <TicketLink class="font-medium" :ticket />
                    <DeviceLink
                        v-if="ticket.device"
                        class="block text-muted-foreground"
                        :device="ticket.device"
                        full
                    />
                </TableCell>

                <TableCell>
                    <CustomerLink
                        v-if="ticket.customer"
                        :customer="ticket.customer"
                    />
                    <p
                        v-if="ticket.customer?.company"
                        class="text-muted-foreground"
                    >
                        {{ ticket.customer.company }}
                    </p>
                </TableCell>

                <TableCell>
                    <UserLink :user="ticket.assignee" />
                </TableCell>

                <TableCell>
                    <Priority :value="ticket.priority" />
                </TableCell>

                <TableCell>
                    <DateTime :value="ticket.due_date" />
                </TableCell>

                <TableCell>
                    <TicketStatusBadge :value="ticket.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="ticket.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
