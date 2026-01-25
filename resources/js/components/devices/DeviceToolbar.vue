<script setup lang="ts">
import { ChangeAction, DeleteAction, EditAction } from '@/components/action';
import { ButtonGroup } from '@/components/ui/button-group';
import { DEVICE_STATUS } from '@/constants';
import { toOptions } from '@/lib/utils';
import { destroy, edit, update } from '@/routes/devices';
import type { Device } from '@/types';

interface Props {
    device: Device;
}

defineProps<Props>();
</script>

<template>
    <ButtonGroup>
        <ButtonGroup>
            <EditAction :href="edit(device)" />
            <DeleteAction record-name="device" :href="destroy(device)" />
        </ButtonGroup>

        <ButtonGroup>
            <ChangeAction
                name="status"
                :data="device"
                :href="update(device)"
                :options="toOptions(DEVICE_STATUS)"
            />
        </ButtonGroup>
    </ButtonGroup>
</template>
