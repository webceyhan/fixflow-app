<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import AttachmentList from '@/components/attachments/AttachmentList.vue';
import AttachmentUploader from '@/components/attachments/AttachmentUploader.vue';
import ImageGallery from '@/components/ImageGallery.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useToast } from '@/composables/useToast';
import type { Attachment } from '@/types';

import {
    destroy,
    download,
    preview,
    store,
    thumbnail,
} from '@/routes/tickets/attachments';

interface Props {
    ticketId: number;
    attachments?: Attachment[];
}

const props = defineProps<Props>();

const toast = useToast();

const isGalleryOpen = ref(false);
const selectedPhotoIndex = ref(0);

const attachments = computed(() => props.attachments ?? []);
const photoAttachments = computed(() => attachments.value.filter(isPhoto));

const galleryImages = computed(() =>
    photoAttachments.value.map(({ id, name }) => ({
        id,
        name,
        url: previewUrl(id),
        thumbnailUrl: thumbnailUrl(id),
    })),
);

const isPhoto = ({ category }: Attachment): boolean => category === 'photo';

const previewUrl = (attachmentId: number): string =>
    preview.url({ ticket: props.ticketId, attachment: attachmentId });

const thumbnailUrl = (attachmentId: number): string =>
    thumbnail.url({ ticket: props.ticketId, attachment: attachmentId });

const downloadAttachment = (attachmentId: number): void => {
    location.href = download.url({
        ticket: props.ticketId,
        attachment: attachmentId,
    });
};

const previewAttachment = (attachmentId: number): void => {
    const index = photoAttachments.value.findIndex(
        ({ id }) => id === attachmentId,
    );

    if (index < 0) {
        return;
    }

    selectedPhotoIndex.value = index;
    isGalleryOpen.value = true;
};

const selectAttachment = (attachment: Attachment): void => {
    return isPhoto(attachment)
        ? previewAttachment(attachment.id)
        : downloadAttachment(attachment.id);
};

const deleteAttachment = (attachmentId: number): void => {
    router.delete(
        destroy.url({
            ticket: props.ticketId,
            attachment: attachmentId,
        }),
        {
            preserveScroll: true,
            onSuccess: () => toast.success('Attachment deleted successfully.'),
        },
    );
};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Attachments</CardTitle>
        </CardHeader>

        <CardContent class="space-y-4">
            <AttachmentList
                v-if="attachments.length"
                :attachments
                :thumbnail-url="thumbnailUrl"
                @select="selectAttachment"
                @delete="deleteAttachment"
            />

            <AttachmentUploader
                :action-url="store.url({ ticket: props.ticketId })"
            />
        </CardContent>
    </Card>

    <ImageGallery
        v-if="galleryImages.length"
        v-model:open="isGalleryOpen"
        :images="galleryImages"
        :initial-index="selectedPhotoIndex"
        @delete="deleteAttachment"
    />
</template>
