<script setup lang="ts">
import { UploadIcon } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    id?: string;
    accept?: string;
    multiple?: boolean;
    disabled?: boolean;
    maxSizeMb?: number;
    title?: string;
    description?: string;
}

withDefaults(defineProps<Props>(), {
    id: undefined,
    accept: '',
    multiple: false,
    disabled: false,
    maxSizeMb: 10,
    title: 'Drag and drop files here, or click to select',
    description: '',
});

const emit = defineEmits<{
    select: [files: File[]];
}>();

const inputRef = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);

function handleDragOver(event: DragEvent): void {
    if (event.dataTransfer) {
        event.dataTransfer.dropEffect = 'copy';
    }

    event.preventDefault();
    isDragging.value = true;
}

function handleDragLeave(event: DragEvent): void {
    event.preventDefault();
    isDragging.value = false;
}

function handleDrop(event: DragEvent): void {
    event.preventDefault();
    isDragging.value = false;

    if (event.dataTransfer?.files) {
        emit('select', Array.from(event.dataTransfer.files));
    }
}

function handleFileSelect(event: Event): void {
    const target = event.target as HTMLInputElement;
    emit('select', target.files ? Array.from(target.files) : []);

    target.value = '';
}

function clear(): void {
    if (inputRef.value) {
        inputRef.value.value = '';
    }

    isDragging.value = false;
}

defineExpose({
    clear,
});
</script>

<template>
    <div
        class="relative rounded-lg border-2 border-dashed p-8 text-center transition-colors"
        :class="{
            'border-primary bg-primary/5': isDragging,
            'border-border': !isDragging,
            'cursor-not-allowed opacity-60': disabled,
        }"
        @dragover="!disabled && handleDragOver($event)"
        @dragleave="!disabled && handleDragLeave($event)"
        @drop="!disabled && handleDrop($event)"
    >
        <input
            :id="id"
            ref="inputRef"
            type="file"
            :accept="accept"
            :multiple="multiple"
            class="absolute inset-0 opacity-0"
            :class="disabled ? 'cursor-not-allowed' : 'cursor-pointer'"
            :disabled="disabled"
            @change="handleFileSelect"
        />

        <UploadIcon class="mx-auto h-12 w-12 text-muted-foreground" />
        <p class="mt-2 text-sm text-muted-foreground">
            {{ title }}
        </p>
        <p class="mt-1 text-xs text-muted-foreground">
            {{ description || `Maximum file size: ${maxSizeMb}MB` }}
        </p>
    </div>
</template>
