import type { UrlMethodPair } from '@inertiajs/core';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useToast } from './useToast';

type Data = Record<string, any>;

type FormConfig<T> = {
    /** For toast messages */
    entityName?: string;
    /** store/update form actions */
    action: UrlMethodPair;
    /** Form field initial values */
    data: T;
};

const toast = useToast();

export function useSaveForm<T extends Data>({
    action,
    data,
    entityName = 'Record',
}: FormConfig<T>) {
    // Determine if we are editing an existing record
    const isEditing = action.method === 'put';

    // Initialize precognitive form
    const form = useForm(action, data);

    const errors = computed(() => form.errors);
    const processing = computed(() => form.processing);
    const isDirty = computed(() => form.dirty);

    // Define success message
    const successMessage = isEditing
        ? `${entityName} updated successfully.`
        : `${entityName} created successfully.`;

    const onSuccess = () => toast.success(successMessage);

    // Override the submit method to include onSuccess callback
    const _submit = form.submit;
    form.submit = () => _submit.call(form, { onSuccess });

    return {
        form,
        errors,
        processing,
        isDirty,
        isEditing,
    };
}
