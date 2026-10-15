<script setup lang="ts">
import { Currency, DateTime } from '@/components/data';
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
import { TransactionIcon, TransactionTypeIcon } from '@/icons';
import { show } from '@/routes/transactions';
import type { Transaction } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import TransactionMethodBadge from './TransactionMethodBadge.vue';

interface Props {
    transactions?: Transaction[];
}

defineProps<Props>();
</script>

<template>
    <Empty v-if="!transactions?.length">
        <EmptyHeader>
            <EmptyMedia>
                <TransactionIcon class="size-12" />
            </EmptyMedia>
            <EmptyTitle>No transactions yet</EmptyTitle>
            <EmptyDescription>
                No transactions have been recorded for this invoice.
            </EmptyDescription>
        </EmptyHeader>
    </Empty>

    <ItemGroup v-else class="gap-2">
        <Item
            v-for="transaction in transactions"
            :key="transaction.id"
            variant="outline"
            :as="Link"
            :href="show(transaction)"
        >
            <ItemMedia>
                <Avatar class="size-10">
                    <AvatarFallback>
                        <TransactionTypeIcon :value="transaction.type" />
                    </AvatarFallback>
                </Avatar>
            </ItemMedia>

            <ItemContent>
                <ItemTitle class="capitalize">
                    {{ transaction.type }}
                    <Badge variant="secondary" class="py-0">
                        {{ transaction.ref }}
                    </Badge>
                </ItemTitle>

                <ItemDescription v-if="transaction.note">
                    {{ transaction.note }}
                </ItemDescription>

                <ItemDescription class="flex items-center gap-4">
                    <TransactionMethodBadge :value="transaction.method" />

                    <Currency :value="transaction.amount" />

                    <DateTime :value="transaction.created_at" />
                </ItemDescription>
            </ItemContent>

            <ItemActions>
                <ChevronRight />
            </ItemActions>
        </Item>
    </ItemGroup>
</template>
