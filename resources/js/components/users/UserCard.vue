<script setup lang="ts">
import { DateTime, Email, Phone, Tick } from '@/components/data';
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
import type { User } from '@/types';
import UserRoleBadge from './UserRoleBadge.vue';
import UserStatusBadge from './UserStatusBadge.vue';

interface Props {
    user: User;
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
                    {{ user.name }}
                </DescriptionListItem>

                <DescriptionListItem title="Email Address">
                    <Email :value="user.email" />
                </DescriptionListItem>

                <DescriptionListItem title="Phone Number">
                    <Phone :value="user.phone" />
                </DescriptionListItem>

                <DescriptionListItem title="Role">
                    <UserRoleBadge :value="user.role" />
                </DescriptionListItem>

                <DescriptionListItem title="Email Verified">
                    <Tick :value="user.email_verified_at" />
                </DescriptionListItem>

                <DescriptionListItem title="2FA Enabled">
                    <Tick :value="user.two_factor_confirmed_at" />
                </DescriptionListItem>

                <DescriptionListItem title="Status">
                    <UserStatusBadge :value="user.status" />
                </DescriptionListItem>

                <DescriptionListItem title="Created On">
                    <DateTime :value="user.created_at" />
                </DescriptionListItem>
            </DescriptionList>
        </CardContent>

        <CardFooter>
            <small class="text-muted-foreground">
                Last update on
                <DateTime :value="user.updated_at" full />
            </small>
        </CardFooter>
    </Card>
</template>
