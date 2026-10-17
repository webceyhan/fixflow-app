<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';

import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { LogOutIcon, SettingsIcon } from '@/icons';
import { logout } from '@/routes';
import { edit } from '@/routes/profile';
import type { User } from '@/types';

import UserInfo from './UserInfo.vue';

interface Props {
    user: User;
}

defineProps<Props>();

const handleLogout = () => {
    router.flushAll();
};
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup>
        <DropdownMenuItem :as-child="true">
            <Link class="block w-full cursor-pointer" :href="edit()" prefetch>
                <SettingsIcon class="mr-2 size-4" />
                Settings
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem :as-child="true">
        <Link
            class="block w-full cursor-pointer"
            :href="logout()"
            @click="handleLogout"
            as="button"
            data-test="logout-button"
        >
            <LogOutIcon class="mr-2 size-4" />
            Log out
        </Link>
    </DropdownMenuItem>
</template>
