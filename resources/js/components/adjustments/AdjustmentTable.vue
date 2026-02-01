<script setup lang="ts">
import { Currency, DateTime, Percent } from '@/components/data';
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
import { AdjustmentTypeIcon } from '@/icons';
import type { Adjustment } from '@/types';
import AdjustmentLink from './AdjustmentLink.vue';
import AdjustmentReasonBadge from './AdjustmentReasonBadge.vue';

interface Props {
    adjustments: Adjustment[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="id"> Adjustment </TableSortLink>
                </TableHead>

                <TableHead> Invoice </TableHead>

                <TableHead> Reason </TableHead>

                <TableHead>
                    <TableSortLink name="amount"> Amount </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="adjustment in adjustments" :key="adjustment.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <AdjustmentTypeIcon :value="adjustment.type" />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <AdjustmentLink class="font-medium" :adjustment />
                    <p class="truncate text-muted-foreground">
                        {{ adjustment.note }}
                    </p>
                </TableCell>

                <TableCell>
                    <InvoiceLink :invoice="adjustment.invoice!" />
                    <TicketLink
                        class="block text-muted-foreground"
                        :ticket="adjustment.invoice?.ticket!"
                    />
                </TableCell>

                <TableCell>
                    <AdjustmentReasonBadge :value="adjustment.reason" />
                </TableCell>

                <TableCell>
                    <Percent
                        v-if="adjustment.is_percent"
                        :value="adjustment.amount"
                    />
                    <Currency v-else :value="adjustment.amount" />
                </TableCell>

                <TableCell>
                    <DateTime :value="adjustment.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
