import type { UrlMethodPair } from '@inertiajs/core';

import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

type Data = Record<string, any>;

type FormConfig<T> = {
    /** store/update form actions */
    action: UrlMethodPair;
    /** Form field initial values */
    data: T;
};

export function useSaveForm<T extends Data>({ action, data }: FormConfig<T>) {
    // Determine if we are editing an existing record
    const isEditing = action.method === 'put';

    // Initialize precognitive form
    const form = useForm(action, data);

    const errors = computed(() => form.errors);
    const processing = computed(() => form.processing);
    const isDirty = computed(() => form.dirty);

    return {
        form,
        errors,
        processing,
        isDirty,
        isEditing,
    };
}
