<script setup lang="ts">
import { DateTime, Priority } from '@/components/data';
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
import UserLink from '@/components/users/UserLink.vue';
import { TicketIcon } from '@/icons';
import { show } from '@/routes/tickets';
import type { Ticket } from '@/types';
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import TicketStatusBadge from './TicketStatusBadge.vue';

interface Props {
    tickets?: Ticket[];
}

defineProps<Props>();
</script>

<template>
    <Empty v-if="!tickets?.length">
        <EmptyHeader>
            <EmptyMedia>
                <TicketIcon class="size-12" />
            </EmptyMedia>
            <EmptyTitle>No tickets yet</EmptyTitle>
            <EmptyDescription>
                You haven't created any tickets yet. Get started by adding your
                first ticket for this device.
            </EmptyDescription>
        </EmptyHeader>
    </Empty>

    <ItemGroup v-else class="gap-2">
        <Item
            v-for="ticket in tickets"
            :key="ticket.id"
            variant="outline"
            :as="Link"
            :href="show(ticket)"
        >
            <ItemMedia>
                <Avatar class="size-10">
                    <AvatarFallback>
                        <TicketIcon />
                    </AvatarFallback>
                </Avatar>
            </ItemMedia>

            <ItemContent>
                <ItemTitle>
                    {{ ticket.title }}
                </ItemTitle>

                <ItemDescription class="flex items-center gap-4">
                    <UserLink
                        :value="ticket.assignee"
                        placeholder="Unassigned"
                    />

                    <Priority :value="ticket.priority" />

                    <TicketStatusBadge :value="ticket.status" />

                    <DateTime :value="ticket.created_at" />
                </ItemDescription>
            </ItemContent>

            <ItemActions>
                <ChevronRight class="size-4 text-muted-foreground" />
            </ItemActions>
        </Item>
    </ItemGroup>
</template>
