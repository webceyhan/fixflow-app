<script setup lang="ts">
import { Currency, DateTime, Tick } from '@/components/data';
import DeviceLink from '@/components/devices/DeviceLink.vue';
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
import { OrderIcon } from '@/icons';
import type { Order } from '@/types';
import OrderLink from './OrderLink.vue';
import OrderStatusBadge from './OrderStatusBadge.vue';

interface Props {
    orders: Order[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="name"> Order </TableSortLink>
                </TableHead>

                <TableHead> Ticket </TableHead>

                <TableHead> Assignee </TableHead>

                <TableHead> Qty </TableHead>

                <TableHead>
                    <TableSortLink name="cost"> Cost </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="due_date"> Due Date </TableSortLink>
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
            <TableRow v-for="order in orders" :key="order.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <OrderIcon />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <OrderLink class="font-medium" :order />
                    <p class="truncate text-muted-foreground">
                        {{ order.supplier }}
                    </p>
                </TableCell>

                <TableCell>
                    <TicketLink :ticket="order.ticket!" />
                    <DeviceLink
                        class="block text-muted-foreground"
                        :device="order.ticket?.device!"
                    />
                </TableCell>

                <TableCell>
                    <UserLink :user="order.assignee" />
                </TableCell>

                <TableCell>
                    {{ order.quantity }}
                </TableCell>

                <TableCell>
                    <Currency :value="order.cost" :off="!order.is_billable" />
                </TableCell>

                <TableCell>
                    <DateTime :value="order.due_date" />
                </TableCell>

                <TableCell>
                    <Tick :value="order.approved_at" />
                </TableCell>

                <TableCell>
                    <OrderStatusBadge :value="order.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="order.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
