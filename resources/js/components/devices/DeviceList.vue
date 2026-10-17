<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

import { DateTime } from '@/components/data';
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
import { DeviceIcon, DeviceTypeIcon } from '@/icons';
import { show } from '@/routes/devices';
import type { Device } from '@/types';

import DeviceStatusBadge from './DeviceStatusBadge.vue';

interface Props {
    devices?: Device[];
}

defineProps<Props>();
</script>

<template>
    <Empty v-if="!devices?.length">
        <EmptyHeader>
            <EmptyMedia>
                <DeviceIcon class="size-12" />
            </EmptyMedia>
            <EmptyTitle>No devices yet</EmptyTitle>
            <EmptyDescription>
                You haven't created any devices yet. Get started by adding your
                first device for this customer.
            </EmptyDescription>
        </EmptyHeader>
    </Empty>

    <ItemGroup v-else class="gap-2">
        <Item
            v-for="device in devices"
            :key="device.id"
            variant="outline"
            :as="Link"
            :href="show(device)"
        >
            <ItemMedia>
                <Avatar class="size-10">
                    <AvatarFallback>
                        <DeviceTypeIcon :value="device.type" />
                    </AvatarFallback>
                </Avatar>
            </ItemMedia>

            <ItemContent>
                <ItemTitle>
                    {{ device.brand }}
                    {{ device.model }}
                    <em v-if="device.color" class="font-extralight capitalize">
                        {{ device.color }}
                    </em>
                </ItemTitle>

                <ItemDescription class="flex items-center gap-4">
                    <span>
                        {{ device.imei ?? device.serial_number ?? '###-####' }}
                    </span>

                    <DeviceStatusBadge :value="device.status" />

                    <DateTime :value="device.created_at" />
                </ItemDescription>
            </ItemContent>

            <ItemActions>
                <ChevronRight class="size-4 text-muted-foreground" />
            </ItemActions>
        </Item>
    </ItemGroup>
</template>
