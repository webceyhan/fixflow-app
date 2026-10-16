<script setup lang="ts">
import { ClockIcon, FileWarningIcon, XIcon } from 'lucide-vue-next';

import {
    AttachmentAction,
    AttachmentActions,
    AttachmentContent,
    AttachmentDescription,
    AttachmentGroup,
    AttachmentMedia,
    AttachmentTitle,
    Attachment as UiAttachment,
} from '@/components/ui/attachment';
import { Spinner } from '@/components/ui/spinner';
import type { QueuedUploadItem } from '@/composables/useUploads';

interface Props {
    uploads: QueuedUploadItem[];
    processing: boolean;
}

defineProps<Props>();

const emit = defineEmits<{
    remove: [uploadId: string];
}>();
</script>

<template>
    <AttachmentGroup
        v-if="uploads.length"
        class="mt-2 flex-col gap-2 overflow-visible py-0 *:data-[slot=attachment]:w-full"
    >
        <UiAttachment
            v-for="upload in uploads"
            :key="upload.id"
            :state="
                upload.status === 'uploading'
                    ? 'uploading'
                    : upload.status === 'failed'
                      ? 'error'
                      : 'idle'
            "
            class="w-full rounded-lg border-2 border-dashed border-border"
        >
            <AttachmentMedia>
                <Spinner v-if="upload.status === 'uploading'" />
                <FileWarningIcon v-else-if="upload.status === 'failed'" />
                <ClockIcon v-else />
            </AttachmentMedia>

            <AttachmentContent>
                <AttachmentTitle>{{ upload.file.name }}</AttachmentTitle>
                <AttachmentDescription v-if="upload.status === 'uploading'">
                    Uploading · {{ upload.progress }}%
                </AttachmentDescription>
                <AttachmentDescription v-else-if="upload.status === 'failed'">
                    Failed to upload
                </AttachmentDescription>
                <AttachmentDescription v-else>
                    Ready to upload
                </AttachmentDescription>

                <div
                    v-if="upload.status === 'uploading'"
                    class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-muted"
                >
                    <div
                        class="h-full bg-primary transition-[width] duration-200 ease-out"
                        :style="{ width: `${upload.progress}%` }"
                    />
                </div>
            </AttachmentContent>

            <AttachmentActions>
                <AttachmentAction
                    aria-label="Remove selected file"
                    :disabled="processing"
                    @click="emit('remove', upload.id)"
                >
                    <XIcon />
                </AttachmentAction>
            </AttachmentActions>
        </UiAttachment>
    </AttachmentGroup>
</template>
