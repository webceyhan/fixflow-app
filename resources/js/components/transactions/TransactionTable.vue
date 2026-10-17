<script setup lang="ts">
import { Currency, DateTime } from '@/components/data';
import InvoiceLink from '@/components/invoices/InvoiceLink.vue';
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
import { TransactionTypeIcon } from '@/icons';
import type { Transaction } from '@/types';

import TransactionLink from './TransactionLink.vue';
import TransactionMethodBadge from './TransactionMethodBadge.vue';

interface Props {
    transactions: Transaction[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="id"> Transaction </TableSortLink>
                </TableHead>

                <TableHead> Invoice </TableHead>

                <TableHead>
                    <TableSortLink name="amount"> Amount </TableSortLink>
                </TableHead>

                <TableHead> Method </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="transaction in transactions" :key="transaction.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <TransactionTypeIcon :value="transaction.type" />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <TransactionLink class="font-medium" :transaction />
                    <p class="truncate text-muted-foreground">
                        {{ transaction.note }}
                    </p>
                </TableCell>

                <TableCell>
                    <InvoiceLink :invoice="transaction.invoice!" />
                    <TicketLink
                        class="block text-muted-foreground"
                        :ticket="transaction.invoice?.ticket!"
                    />
                </TableCell>

                <TableCell>
                    <Currency :value="transaction.amount" />
                </TableCell>

                <TableCell>
                    <TransactionMethodBadge :value="transaction.method" />
                </TableCell>

                <TableCell>
                    <DateTime :value="transaction.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
