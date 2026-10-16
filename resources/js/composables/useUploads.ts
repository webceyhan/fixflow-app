import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

export interface QueuedUploadItem {
    id: string;
    file: File;
    status: 'ready' | 'uploading' | 'failed';
    progress: number;
}

interface UseUploadsOptions {
    action: string;
    onSuccess?: (uploadedCount: number) => void;
    onFinish?: (result: { hasPendingUploads: boolean }) => void;
}

export function useUploads({
    action,
    onSuccess = () => {},
    onFinish = () => {},
}: UseUploadsOptions) {
    const form = useForm<{
        file: File | null;
    }>({
        file: null,
    });
    const queuedUploads = ref<QueuedUploadItem[]>([]);
    const processing = ref(false);
    const customError = ref('');
    const hasPendingUploads = computed(() => queuedUploads.value.length > 0);
    const error = computed(() => customError.value || form.errors.file || '');

    const uploadSingleFile = (
        file: File,
        onProgress: (progress: number) => void,
    ): Promise<{ success: boolean; error?: string }> => {
        return new Promise((resolve) => {
            form.file = file;

            form.post(action, {
                forceFormData: true,
                preserveScroll: true,
                preserveState: true,
                onProgress: (event) => onProgress(event?.percentage ?? 0),
                onSuccess: () => resolve({ success: true }),
                onError: () =>
                    resolve({
                        success: false,
                        error: form.errors.file,
                    }),
            });
        });
    };

    const selectFiles = (files: File[]) => {
        queuedUploads.value = files.map((file, index) => ({
            id: `${file.name}-${file.size}-${file.lastModified}-${index}`,
            file,
            status: 'ready',
            progress: 0,
        }));
        customError.value = '';
        form.clearErrors('file');
    };

    const removeQueuedUpload = (uploadId: string) => {
        queuedUploads.value = queuedUploads.value.filter(
            (upload) => upload.id !== uploadId,
        );

        if (!queuedUploads.value.length) {
            customError.value = '';
            form.clearErrors('file');
        }
    };

    const clearQueuedUploads = () => {
        queuedUploads.value = [];
        customError.value = '';
        form.reset('file');
        form.clearErrors('file');
    };

    const uploadQueuedFiles = async () => {
        if (!queuedUploads.value.length || processing.value) {
            return;
        }

        processing.value = true;
        customError.value = '';
        form.clearErrors('file');

        const uploads: QueuedUploadItem[] = queuedUploads.value.map((item) => ({
            ...item,
            status: 'ready',
            progress: 0,
        }));

        queuedUploads.value = uploads;
        let firstFailureError = '';

        for (const upload of uploads) {
            upload.status = 'uploading';
            upload.progress = 0;

            const result = await uploadSingleFile(upload.file, (progress) => {
                upload.progress = progress;
            });

            if (!result.success) {
                upload.status = 'failed';

                if (!firstFailureError && result.error) {
                    firstFailureError = result.error;
                }
            } else {
                upload.progress = 100;
            }
        }

        processing.value = false;

        const failedUploads = uploads.filter(
            (upload) => upload.status === 'failed',
        );
        const successCount = uploads.length - failedUploads.length;

        if (successCount) {
            onSuccess(successCount);
        }

        if (failedUploads.length) {
            customError.value =
                failedUploads.length === 1
                    ? firstFailureError ||
                      `Failed to upload: ${failedUploads[0]?.file.name ?? 'selected file'}`
                    : `Failed to upload ${failedUploads.length} files.`;

            queuedUploads.value = failedUploads.map((upload) => ({
                ...upload,
                status: 'ready',
                progress: 0,
            }));
        } else {
            clearQueuedUploads();
        }

        onFinish({ hasPendingUploads: hasPendingUploads.value });
    };

    return {
        queuedUploads,
        processing,
        error,
        hasPendingUploads,
        selectFiles,
        removeQueuedUpload,
        clearQueuedUploads,
        uploadQueuedFiles,
    };
}
