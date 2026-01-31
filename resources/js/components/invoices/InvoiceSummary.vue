<script setup lang="ts">
import { Currency } from '@/components/data';
import {
    DescriptionList,
    DescriptionListItem,
} from '@/components/ui/description-list';
import { Separator } from '@/components/ui/separator';
import type { Invoice } from '@/types';
import { computed } from 'vue';

interface Props {
    invoice: Invoice;
}

const { invoice } = defineProps<Props>();

const subTotal = computed(() => invoice.task_total + invoice.order_total);

const netTotal = computed(
    () => subTotal.value + invoice.fee_amount - invoice.discount_amount,
);

const balanceDue = computed(
    () => netTotal.value - invoice.paid_amount - invoice.refunded_amount,
);
</script>

<template>
    <DescriptionList
        class="-my-1 divide-y-0 *:data-[slot=description-list-item]:py-2 [&_dd]:text-end [&>div]:grid-cols-[1fr_auto]"
    >
        <DescriptionListItem v-if="invoice.task_total" title="Tasks">
            <Currency :value="invoice.task_total" />
        </DescriptionListItem>

        <DescriptionListItem v-if="invoice.order_total" title="Orders">
            <Currency :value="invoice.order_total" />
        </DescriptionListItem>

        <DescriptionListItem v-if="invoice.fee_amount" title="Fees">
            +<Currency :value="invoice.fee_amount" />
        </DescriptionListItem>

        <DescriptionListItem v-if="invoice.discount_amount" title="Discounts">
            -<Currency :value="invoice.discount_amount" />
        </DescriptionListItem>

        <Separator />

        <DescriptionListItem title="Total">
            <Currency class="text-xl font-extrabold" :value="invoice.total" />
        </DescriptionListItem>

        <Separator />

        <DescriptionListItem v-if="invoice.paid_amount" title="Paid">
            -<Currency :value="invoice.paid_amount" />
        </DescriptionListItem>

        <DescriptionListItem v-if="invoice.refunded_amount" title="Refunded">
            +<Currency :value="invoice.refunded_amount" />
        </DescriptionListItem>

        <Separator />

        <DescriptionListItem title="Balance Due">
            <Currency
                :class="[
                    'text-xl font-extrabold',
                    balanceDue > 0 ? 'text-error!' : 'text-success!',
                ]"
                :value="balanceDue"
            />
        </DescriptionListItem>
    </DescriptionList>
</template>
