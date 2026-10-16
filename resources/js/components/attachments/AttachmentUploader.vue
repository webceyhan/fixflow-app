<script setup lang="ts">
import { UploadIcon } from 'lucide-vue-next';
import { ref } from 'vue';

import FileDropzone from '@/components/FileDropzone.vue';
import QueuedUploadList from '@/components/QueuedUploadList.vue';
import { Button } from '@/components/ui/button';
import { FieldError } from '@/components/ui/field';
import { useToast } from '@/composables/useToast';
import { useUploads } from '@/composables/useUploads';

interface Props {
    actionUrl: string;
}

const props = defineProps<Props>();

const toast = useToast();
const dropzone = ref<InstanceType<typeof FileDropzone> | null>(null);

const {
    queuedUploads,
    processing,
    error,
    hasPendingUploads,
    selectFiles,
    removeQueuedUpload,
    uploadQueuedFiles,
} = useUploads({
    action: props.actionUrl,
    onSuccess: (successCount) => {
        toast.success(
            successCount === 1
                ? '1 attachment uploaded successfully.'
                : `${successCount} attachments uploaded successfully.`,
        );
    },
    onFinish: ({ hasPendingUploads }) => {
        if (!hasPendingUploads) {
            dropzone.value?.clear();
        }
    },
});
</script>

<template>
    <form class="space-y-2" @submit.prevent="uploadQueuedFiles">
        <FileDropzone
            ref="dropzone"
            id="attachment-uploader"
            accept=".jpg,.jpeg,.png,.webp,.pdf,.txt,.doc,.docx,.xls,.xlsx"
            :multiple="true"
            :disabled="processing"
            @select="selectFiles"
        />

        <QueuedUploadList
            :uploads="queuedUploads"
            :processing="processing"
            @remove="removeQueuedUpload"
        />

        <Button
            v-if="hasPendingUploads"
            type="submit"
            class="mt-2 w-full"
            :disabled="processing || !hasPendingUploads"
        >
            <UploadIcon />
            Upload
        </Button>

        <FieldError :message="error" />
    </form>
</template>
