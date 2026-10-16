<script setup lang="ts">
import { TrashIcon, XIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Button } from '@/components/ui/button';
import type { CarouselApi } from '@/components/ui/carousel';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from '@/components/ui/carousel';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface ImageGalleryItem {
    id: number;
    name: string;
    url: string;
    thumbnailUrl?: string;
}

interface Props {
    images: ImageGalleryItem[];
    initialIndex?: number;
}

const props = withDefaults(defineProps<Props>(), {
    initialIndex: 0,
});

const emit = defineEmits<{
    delete: [attachmentId: number];
}>();

const open = defineModel<boolean>('open', { default: false });
const carouselApi = ref<CarouselApi>();
const currentIndex = ref(props.initialIndex);
const currentImage = computed(() => props.images[currentIndex.value] ?? null);

watch(
    () => props.initialIndex,
    (index) => {
        if (index < 0 || index >= props.images.length) {
            return;
        }

        currentIndex.value = index;
    },
);

function setApi(api: CarouselApi): void {
    carouselApi.value = api;
}

watch(open, (isOpen) => {
    if (isOpen) {
        currentIndex.value = props.initialIndex;

        setTimeout(() => {
            carouselApi.value?.scrollTo(props.initialIndex);
        }, 0);
    }
});

watch(carouselApi, (api) => {
    if (!api) {
        return;
    }

    currentIndex.value = api.selectedScrollSnap();

    api.on('select', () => {
        currentIndex.value = api.selectedScrollSnap();
    });
});

function goToIndex(index: number): void {
    carouselApi.value?.scrollTo(index);
}

function removeCurrentImage(): void {
    if (!currentImage.value) {
        return;
    }

    const deletingAttachmentId = currentImage.value.id;
    const imageCount = props.images.length;

    if (imageCount <= 1) {
        open.value = false;
    } else if (currentIndex.value === imageCount - 1) {
        currentIndex.value -= 1;
    }

    emit('delete', deletingAttachmentId);
}

function fallbackToMainImage(event: Event, fallbackUrl: string): void {
    const imageElement = event.target as HTMLImageElement | null;

    if (!imageElement || imageElement.src === fallbackUrl) {
        return;
    }

    imageElement.src = fallbackUrl;
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogContent
            class="h-screen max-w-full min-w-screen border-0 p-0 sm:rounded-none"
            :show-close-button="false"
            @pointer-down-outside.prevent
        >
            <div
                class="relative flex h-full min-h-0 flex-col items-center justify-center gap-3 p-6"
            >
                <DialogClose class="absolute top-6 right-6 z-20">
                    <XIcon />
                </DialogClose>

                <DialogHeader class="sr-only">
                    <DialogTitle>{{ currentImage?.name }}</DialogTitle>
                    <DialogDescription />
                </DialogHeader>

                <div
                    class="flex h-full w-full flex-col items-center justify-center gap-8"
                >
                    <Carousel class="w-full xl:max-w-2/3" @init-api="setApi">
                        <CarouselContent>
                            <CarouselItem
                                v-for="image in images"
                                :key="image.id"
                                class="relative"
                            >
                                <AspectRatio :ratio="16 / 9" class="w-full">
                                    <img
                                        :src="image.url"
                                        :alt="image.name"
                                        class="h-full w-full rounded-lg bg-black object-cover"
                                    />
                                </AspectRatio>

                                <div class="absolute right-4 bottom-4">
                                    <Button
                                        variant="destructive"
                                        size="icon-lg"
                                        @click="removeCurrentImage"
                                    >
                                        <TrashIcon />
                                    </Button>
                                </div>
                            </CarouselItem>
                        </CarouselContent>

                        <CarouselPrevious />
                        <CarouselNext />
                    </Carousel>

                    <div
                        v-if="images.length > 1"
                        class="flex max-w-full gap-2 overflow-x-auto px-4"
                    >
                        <button
                            v-for="(image, index) in images"
                            :key="image.id"
                            type="button"
                            class="relative size-20 shrink-0 overflow-hidden rounded-md border-2 transition-all"
                            :class="
                                index === currentIndex
                                    ? 'border-primary'
                                    : 'border-transparent opacity-60 hover:opacity-100'
                            "
                            @click="goToIndex(index)"
                        >
                            <img
                                :src="image.thumbnailUrl ?? image.url"
                                :alt="image.name"
                                class="h-full w-full object-cover"
                                @error="fallbackToMainImage($event, image.url)"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
