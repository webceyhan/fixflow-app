<script setup lang="ts">
import { DateTime, Priority } from '@/components/data';
import DeviceLink from '@/components/devices/DeviceLink.vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    DescriptionList,
    DescriptionListItem,
} from '@/components/ui/description-list';
import UserLink from '@/components/users/UserLink.vue';
import type { Ticket } from '@/types';

import TicketStatusBadge from './TicketStatusBadge.vue';

interface Props {
    ticket: Ticket;
}

defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Overview</CardTitle>
        </CardHeader>

        <CardContent>
            <DescriptionList>
                <DescriptionListItem title="Title">
                    {{ ticket.title }}
                </DescriptionListItem>

                <DescriptionListItem v-if="ticket.device" title="Device">
                    <DeviceLink :device="ticket.device" full />
                </DescriptionListItem>

                <DescriptionListItem title="Assignee">
                    <UserLink :user="ticket.assignee" />
                </DescriptionListItem>

                <DescriptionListItem title="Priority">
                    <Priority :value="ticket.priority" />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <TicketStatusBadge :value="ticket.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Due Date">
                    <DateTime :value="ticket.due_date" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="ticket.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="ticket.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
