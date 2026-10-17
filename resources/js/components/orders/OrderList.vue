<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

import { Currency, DateTime, Tick } from '@/components/data';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
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
import { OrderIcon } from '@/icons';
import { show } from '@/routes/orders';
import type { Order } from '@/types';

import OrderStatusBadge from './OrderStatusBadge.vue';

interface Props {
    orders?: Order[];
}

defineProps<Props>();
</script>

<template>
    <Empty v-if="!orders?.length">
        <EmptyHeader>
            <EmptyMedia>
                <OrderIcon class="size-12" />
            </EmptyMedia>
            <EmptyTitle>No orders yet</EmptyTitle>
            <EmptyDescription>
                You haven't created any orders yet. Get started by adding your
                first order for this ticket.
            </EmptyDescription>
        </EmptyHeader>
    </Empty>

    <ItemGroup v-else class="gap-2">
        <Item
            v-for="order in orders"
            :key="order.id"
            variant="outline"
            :as="Link"
            :href="show(order)"
        >
            <ItemMedia>
                <Avatar class="size-10">
                    <AvatarFallback>
                        <OrderIcon />
                    </AvatarFallback>
                </Avatar>
            </ItemMedia>

            <ItemContent>
                <ItemTitle>
                    {{ order.name }}
                    <Badge variant="secondary" class="py-0">
                        {{ order.ref }}
                    </Badge>
                </ItemTitle>

                <ItemDescription>
                    Supplied by {{ order.supplier }}
                </ItemDescription>

                <ItemDescription v-if="order.note">
                    {{ order.note }}
                </ItemDescription>

                <ItemDescription class="flex items-center gap-4">
                    <span>Qty: {{ order.quantity }}</span>

                    <Currency :value="order.cost" :off="!order.is_billable" />

                    <Tick :value="order.approved_at" pending> Approval </Tick>

                    <OrderStatusBadge :value="order.status" />

                    <DateTime :value="order.due_date" />
                </ItemDescription>
            </ItemContent>

            <ItemActions>
                <ChevronRight class="size-4 text-muted-foreground" />
            </ItemActions>
        </Item>
    </ItemGroup>
</template>
