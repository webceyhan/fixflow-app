<script setup lang="ts">
import { XIcon } from 'lucide-vue-next';

import {
    AttachmentAction,
    AttachmentActions,
    AttachmentContent,
    AttachmentDescription,
    AttachmentGroup,
    Attachment as AttachmentItem,
    AttachmentMedia,
    AttachmentTitle,
    AttachmentTrigger,
} from '@/components/ui/attachment';
import { AttachmentCategoryIcon } from '@/icons';
import { formatSize } from '@/lib/utils';
import type { Attachment } from '@/types';

interface Props {
    attachments?: Attachment[];
    thumbnailUrl: (id: number) => string;
}

defineProps<Props>();

defineEmits<{
    (e: 'select', attachment: Attachment): void;
    (e: 'delete', attachmentId: number): void;
}>();

const isPhoto = ({ category }: Attachment): boolean => {
    return category === 'photo';
};

const hideOnError = (event: Event): void => {
    const imageElement = event.target as HTMLImageElement | null;

    if (imageElement) {
        imageElement.style.display = 'none';
    }
};
</script>

<template>
    <AttachmentGroup class="w-full">
        <AttachmentItem
            v-for="attachment in attachments ?? []"
            :key="attachment.id"
            orientation="vertical"
            state="done"
            class="w-28"
        >
            <AttachmentMedia variant="image">
                <img
                    v-if="isPhoto(attachment)"
                    :src="thumbnailUrl(attachment.id)"
                    :alt="attachment.name"
                    @error="hideOnError"
                />

                <AttachmentCategoryIcon
                    class="size-12"
                    :value="attachment.category"
                />
            </AttachmentMedia>

            <AttachmentContent>
                <AttachmentTitle>
                    {{ attachment.name }}
                </AttachmentTitle>
                <AttachmentDescription>
                    {{ formatSize(attachment.size) }}
                </AttachmentDescription>
            </AttachmentContent>

            <AttachmentActions>
                <AttachmentAction
                    aria-label="Delete attachment"
                    @click.stop.prevent="$emit('delete', attachment.id)"
                >
                    <XIcon />
                </AttachmentAction>
            </AttachmentActions>

            <AttachmentTrigger as-child>
                <button @click="$emit('select', attachment)" />
            </AttachmentTrigger>
        </AttachmentItem>
    </AttachmentGroup>
</template>
