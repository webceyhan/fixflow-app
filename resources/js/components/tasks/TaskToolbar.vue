<script setup lang="ts">
import {
    AssignAction,
    ChangeAction,
    DeleteAction,
    EditAction,
} from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { TASK_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { destroy, edit, update } from '@/routes/tasks';
import type { Task } from '@/types';

interface Props {
    task: Task;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(task)" />
            <DeleteAction record-name="task" :href="destroy(task)" />
        </ButtonGroup>

        <ButtonGroup>
            <AssignAction :href="update(task)" :data="task" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="task"
                :href="update(task)"
                :options="toOptions(TASK_STATUS)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
