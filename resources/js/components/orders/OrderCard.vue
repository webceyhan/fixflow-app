<script setup lang="ts">
import { Currency, DateTime } from '@/components/data';
import TextLink from '@/components/TextLink.vue';
import TicketLink from '@/components/tickets/TicketLink.vue';
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
import type { Order } from '@/types';

import OrderStatusBadge from './OrderStatusBadge.vue';

interface Props {
    order: Order;
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
                <DescriptionListItem title="Name">
                    {{ order.name }}
                </DescriptionListItem>

                <DescriptionListItem title="Supplier">
                    {{ order.supplier || '-' }}
                </DescriptionListItem>

                <DescriptionListItem v-if="order.url" title="URL">
                    <TextLink
                        :href="order.url"
                        rel="noopener noreferrer"
                        target="_blank"
                        native
                    >
                        {{ order.url }}
                    </TextLink>
                </DescriptionListItem>

                <DescriptionListItem title="Ticket">
                    <TicketLink :ticket="order.ticket!" />
                </DescriptionListItem>

                <DescriptionListItem title="Assignee">
                    <UserLink :user="order.assignee" />
                </DescriptionListItem>

                <DescriptionListItem title="Quantity">
                    {{ order.quantity }}
                </DescriptionListItem>

                <DescriptionListItem title="Cost">
                    <Currency :value="order.cost" :off="!order.is_billable" />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <OrderStatusBadge :value="order.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Due Date">
                    <DateTime :value="order.due_date" />
                </DescriptionListItem>

                <DescriptionListItem title="Approval">
                    <DateTime :value="order.approved_at" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="order.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="order.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
