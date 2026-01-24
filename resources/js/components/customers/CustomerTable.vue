<script setup lang="ts">
import { DateTime, Email, Phone } from '@/components/data';
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
import { CustomerTypeIcon } from '@/icons';
import type { Customer } from '@/types';
import CustomerLink from './CustomerLink.vue';
import CustomerStatusBadge from './CustomerStatusBadge.vue';

interface Props {
    customers: Customer[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="name"> Name </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="email"> Email </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="phone"> Phone </TableSortLink>
                </TableHead>

                <TableHead> Status </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="customer in customers" :key="customer.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <CustomerTypeIcon :value="customer.type" />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <CustomerLink class="font-medium" :customer />
                    <p class="text-muted-foreground">
                        {{ customer?.company ?? '' }}
                    </p>
                </TableCell>

                <TableCell>
                    <Email :value="customer.email" />
                </TableCell>

                <TableCell>
                    <Phone :value="customer.phone" />
                </TableCell>

                <TableCell>
                    <CustomerStatusBadge :value="customer.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="customer.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
