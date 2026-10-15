<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

import { Currency, DateTime, Percent } from '@/components/data';
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
import { AdjustmentIcon, AdjustmentTypeIcon } from '@/icons';
import { show } from '@/routes/adjustments';
import type { Adjustment } from '@/types';

import AdjustmentReasonBadge from './AdjustmentReasonBadge.vue';

interface Props {
    adjustments?: Adjustment[];
}

defineProps<Props>();
</script>

<template>
    <Empty v-if="!adjustments?.length">
        <EmptyHeader>
            <EmptyMedia>
                <AdjustmentIcon class="size-12" />
            </EmptyMedia>
            <EmptyTitle>No adjustments yet</EmptyTitle>
            <EmptyDescription>
                No adjustments have been recorded for this invoice.
            </EmptyDescription>
        </EmptyHeader>
    </Empty>

    <ItemGroup v-else class="gap-2">
        <Item
            v-for="adjustment in adjustments"
            :key="adjustment.id"
            variant="outline"
            :as="Link"
            :href="show(adjustment)"
        >
            <ItemMedia>
                <Avatar class="size-10">
                    <AvatarFallback>
                        <AdjustmentTypeIcon :value="adjustment.type" />
                    </AvatarFallback>
                </Avatar>
            </ItemMedia>

            <ItemContent>
                <ItemTitle class="capitalize">
                    {{ adjustment.type }}
                    <Badge variant="secondary" class="py-0">
                        {{ adjustment.ref }}
                    </Badge>
                </ItemTitle>

                <ItemDescription v-if="adjustment.note">
                    {{ adjustment.note }}
                </ItemDescription>

                <ItemDescription class="flex items-center gap-4">
                    <AdjustmentReasonBadge :value="adjustment.reason" />

                    <Percent
                        v-if="adjustment.is_percent"
                        :value="adjustment.amount"
                    />
                    <Currency v-else :value="adjustment.amount" />

                    <DateTime :value="adjustment.created_at" />
                </ItemDescription>
            </ItemContent>

            <ItemActions>
                <ChevronRight />
            </ItemActions>
        </Item>
    </ItemGroup>
</template>
