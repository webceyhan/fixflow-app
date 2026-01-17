<script setup lang="ts">
import { ChangeAction, DeleteAction, EditAction } from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { USER_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { destroy, edit, update } from '@/routes/users';
import type { User } from '@/types';

interface Props {
    user: User;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(user)" />
            <DeleteAction record-name="user" :href="destroy(user)" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="user"
                :href="update(user)"
                :options="toOptions(USER_STATUS)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
