<script setup lang="ts">
import CustomerLink from '@/components/customers/CustomerLink.vue';
import { DateTime } from '@/components/data';
import TicketLink from '@/components/tickets/TicketLink.vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import CardAction from '@/components/ui/card/CardAction.vue';
import {
    DescriptionList,
    DescriptionListItem,
} from '@/components/ui/description-list';
import type { Invoice } from '@/types';
import InvoiceLink from './InvoiceLink.vue';
import InvoiceStatusBadge from './InvoiceStatusBadge.vue';
import InvoiceSummary from './InvoiceSummary.vue';

interface Props {
    invoice: Invoice;
    embedded?: boolean;
}

defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Overview</CardTitle>
            <CardAction v-if="embedded">
                <InvoiceLink :invoice />
            </CardAction>
        </CardHeader>

        <CardContent>
            <DescriptionList>
                <DescriptionListItem v-if="invoice.ticket" title="Ticket">
                    <TicketLink :ticket="invoice.ticket" />
                </DescriptionListItem>

                <DescriptionListItem
                    v-if="invoice.ticket?.customer"
                    title="Customer"
                >
                    <CustomerLink :customer="invoice.ticket.customer" />
                </DescriptionListItem>

                <DescriptionListItem title="Summary">
                    <InvoiceSummary :invoice />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <InvoiceStatusBadge :value="invoice.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Due Date">
                    <DateTime :value="invoice.due_date" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="invoice.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="invoice.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
