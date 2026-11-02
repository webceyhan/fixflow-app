<script setup lang="ts">
import { QrCode } from '@lucide/vue';

import type { Ticket } from '@/types';

import { DateTime, Priority } from '@/components/data';
import DeviceLink from '@/components/devices/DeviceLink.vue';
import { Button } from '@/components/ui/button';
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
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
} from '@/components/ui/hover-card';
import UserLink from '@/components/users/UserLink.vue';
import { qrCode } from '@/routes/tickets';

import TicketStatusBadge from './TicketStatusBadge.vue';

type Props = {
    ticket: Ticket;
};

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

                <DescriptionListItem title="QR Code">
                    <HoverCard :open-delay="200">
                        <HoverCardTrigger as-child>
                            <Button variant="ghost" size="icon" class="-my-4">
                                <QrCode />
                            </Button>
                        </HoverCardTrigger>
                        <HoverCardContent class="w-64">
                            <img
                                :src="qrCode(ticket).url"
                                alt="QR Code"
                                class="w-full"
                            />
                        </HoverCardContent>
                    </HoverCard>
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
