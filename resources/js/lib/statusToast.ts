import { router, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

export function initializeStatusToast() {
    const page = usePage();

    const mapping: Record<string, string> = {
        'verification-link-sent':
            'A new verification link has been sent to your email address.',
    };

    router.on('finish', () => {
        const status = page.props?.status as string;

        if (status) {
            toast.success(mapping[status] ?? status);
        }
    });
}
