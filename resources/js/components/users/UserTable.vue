<script setup lang="ts">
import { DateTime, Email, Phone, Tick } from '@/components/data';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
    TableSortLink,
} from '@/components/ui/table';
import { UserRoleIcon } from '@/icons';
import type { User } from '@/types';

import UserLink from './UserLink.vue';
import UserRoleBadge from './UserRoleBadge.vue';
import UserStatusBadge from './UserStatusBadge.vue';

interface Props {
    users: User[];
}

defineProps<Props>();
</script>

<template>
    <Table class="border-y">
        <TableHeader>
            <TableRow class="bg-muted/50">
                <TableHead colspan="2">
                    <TableSortLink name="name"> User </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="email"> Email </TableSortLink>
                </TableHead>

                <TableHead>
                    <TableSortLink name="phone"> Phone </TableSortLink>
                </TableHead>

                <TableHead> Verified </TableHead>

                <TableHead> Status </TableHead>

                <TableHead>
                    <TableSortLink name="created_at"> Created </TableSortLink>
                </TableHead>
            </TableRow>
        </TableHeader>

        <TableBody>
            <TableRow v-for="user in users" :key="user.id">
                <TableCell class="w-10">
                    <Avatar class="size-10">
                        <AvatarFallback>
                            <UserRoleIcon :value="user.role" />
                        </AvatarFallback>
                    </Avatar>
                </TableCell>

                <TableCell>
                    <UserLink class="font-medium" :user />
                    <UserRoleBadge
                        class="block text-muted-foreground"
                        :value="user.role"
                    />
                </TableCell>

                <TableCell>
                    <Email :value="user.email" />
                </TableCell>

                <TableCell>
                    <Phone :value="user.phone" />
                </TableCell>

                <TableCell>
                    <Tick :value="user.email_verified_at" />
                </TableCell>

                <TableCell>
                    <UserStatusBadge :value="user.status" />
                </TableCell>

                <TableCell>
                    <DateTime :value="user.created_at" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>
