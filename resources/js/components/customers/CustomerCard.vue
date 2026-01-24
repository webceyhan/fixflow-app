<script setup lang="ts">
import { Address, DateTime, Email, Phone } from '@/components/data';
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
import type { Customer } from '@/types';
import CustomerStatusBadge from './CustomerStatusBadge.vue';
import CustomerTypeBadge from './CustomerTypeBadge.vue';

interface Props {
    customer: Customer;
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
                <DescriptionListItem title="Full Name">
                    {{ customer.name }}
                </DescriptionListItem>

                <template v-if="customer.company">
                    <DescriptionListItem title="Company">
                        {{ customer.company }}
                    </DescriptionListItem>

                    <DescriptionListItem title="Tax Number">
                        {{ customer.tax_number }}
                    </DescriptionListItem>
                </template>

                <DescriptionListItem title="Email Address">
                    <Email :value="customer.email" />
                </DescriptionListItem>

                <DescriptionListItem title="Phone Number">
                    <Phone :value="customer.phone" />
                </DescriptionListItem>

                <DescriptionListItem title="Address">
                    <Address :value="customer.address" />
                </DescriptionListItem>

                <DescriptionListItem title="Type">
                    <CustomerTypeBadge :value="customer.type" />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <CustomerStatusBadge :value="customer.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="customer.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="customer.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
