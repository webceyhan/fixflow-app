import { usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
import { toast } from 'vue-sonner';

/**
 * Watches the Inertia page status and shows corresponding toast notifications.
 */
function watchStatus() {
    const page = usePage();

    const mapping: Record<string, string> = {
        'verification-link-sent':
            'A new verification link has been sent to your email address.',
    };

    watch(
        () => page.props.status as any,
        (status) => toast.success(mapping[status] ?? status),
    );
}

export function useToast() {
    return {
        ...toast,
        watchStatus,
    };
}
