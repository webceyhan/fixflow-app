<script setup lang="ts">
import CustomerLink from '@/components/customers/CustomerLink.vue';
import { Currency, DateTime } from '@/components/data';
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
import { InvoiceIcon } from '@/icons';
import type { Invoice } from '@/types';
import InvoiceLink from './InvoiceLink.vue';
import InvoiceStatusBadge from './InvoiceStatusBadge.vue';

interface Props {
    invoices: Invoice[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="id"> Invoice </TableSortLink>
                </TableHead>

                <TableHead> Ticket </TableHead>

                <TableHead>
                    <TableSortLink name="total"> Total </TableSortLink>
                </TableHead>

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
            <TableRow v-for="invoice in invoices" :key="invoice.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <InvoiceIcon />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <InvoiceLink class="font-medium" :invoice />
                    <CustomerLink
                        class="block text-muted-foreground"
                        :customer="invoice.ticket?.customer!"
                    />
                </TableCell>

                <TableCell>
                    <TicketLink :ticket="invoice.ticket!" />
                    <DeviceLink
                        class="block text-muted-foreground"
                        :device="invoice.ticket?.device!"
                    />
                </TableCell>

                <TableCell>
                    <Currency :value="invoice.total" />
                </TableCell>

                <TableCell>
                    <DateTime :value="invoice.due_date" />
                </TableCell>

                <TableCell>
                    <InvoiceStatusBadge :value="invoice.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="invoice.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
