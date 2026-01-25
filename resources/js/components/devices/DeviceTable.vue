<script setup lang="ts">
import CustomerLink from '@/components/customers/CustomerLink.vue';
import { DateTime } from '@/components/data';
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
import { DeviceTypeIcon } from '@/icons';
import type { Device } from '@/types';
import DeviceLink from './DeviceLink.vue';
import DeviceStatusBadge from './DeviceStatusBadge.vue';
import DeviceTypeBadge from './DeviceTypeBadge.vue';

interface Props {
    devices: Device[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="model"> Device </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="brand"> Brand </TableSortLink>
                </TableHead>

                <TableHead> Customer </TableHead>

                <TableHead> Status </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="device in devices" :key="device.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <DeviceTypeIcon :value="device.type" />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <DeviceLink class="font-medium" :device />
                    <DeviceTypeBadge
                        class="block text-muted-foreground"
                        :value="device.type"
                    />
                </TableCell>

                <TableCell>
                    {{ device.brand }}
                </TableCell>

                <TableCell>
                    <CustomerLink
                        v-if="device.customer"
                        :customer="device.customer"
                    />
                    <p
                        v-if="device.customer?.company"
                        class="text-muted-foreground"
                    >
                        {{ device.customer.company }}
                    </p>
                </TableCell>

                <TableCell>
                    <DeviceStatusBadge :value="device.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="device.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
