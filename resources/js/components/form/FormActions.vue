<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Field } from '@/components/ui/field';

interface Props {
    processing: boolean;
    isEditing?: boolean;
}

defineProps<Props>();

function handleCancel() {
    window.history.back();
}
</script>

<template>
    <Field orientation="horizontal">
        <Button type="submit" :disabled="processing">
            {{ isEditing ? 'Update' : 'Create' }}
        </Button>
        <Button
            type="button"
            variant="outline"
            :disabled="processing"
            @click="handleCancel"
        >
            Cancel
        </Button>
    </Field>
</template>
