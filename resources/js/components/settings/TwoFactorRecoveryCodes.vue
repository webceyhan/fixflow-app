<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Eye, EyeOffIcon, LockKeyhole, RefreshCw } from 'lucide-vue-next';
import { nextTick, onMounted, ref, useTemplateRef } from 'vue';

import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import { useToast } from '@/composables/useToast';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { regenerateRecoveryCodes } from '@/routes/two-factor';

const toast = useToast();
const { recoveryCodesList, fetchRecoveryCodes } = useTwoFactorAuth();
const isRecoveryCodesVisible = ref<boolean>(false);
const recoveryCodeSectionRef = useTemplateRef('recoveryCodeSectionRef');

const toggleRecoveryCodesVisibility = async () => {
    if (!isRecoveryCodesVisible.value && !recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }

    isRecoveryCodesVisible.value = !isRecoveryCodesVisible.value;

    if (isRecoveryCodesVisible.value) {
        await nextTick();
        recoveryCodeSectionRef.value?.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(async () => {
    if (!recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }
});
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle class="flex gap-2">
                <LockKeyhole class="size-4" />
                2FA Recovery Codes
            </CardTitle>
            <CardDescription>
                Recovery codes let you regain access if you lose your 2FA
                device. Store them in a secure password manager.
            </CardDescription>
        </CardHeader>

        <CardContent>
            <nav class="flex items-center justify-between gap-2 select-none">
                <Button @click="toggleRecoveryCodesVisibility" size="sm">
                    <template v-if="isRecoveryCodesVisible">
                        <EyeOffIcon />
                        Hide Recovery Codes
                    </template>
                    <template v-else>
                        <Eye />
                        Show Recovery Codes
                    </template>
                </Button>

                <Form
                    v-if="isRecoveryCodesVisible && recoveryCodesList.length"
                    v-bind="regenerateRecoveryCodes.form()"
                    :options="{ preserveScroll: true }"
                    @success="
                        () => {
                            fetchRecoveryCodes();
                            toast.success('Recovery codes regenerated!');
                        }
                    "
                    #default="{ processing }"
                >
                    <Button variant="ghost" size="sm" :disabled="processing">
                        <RefreshCw />
                    </Button>
                </Form>
            </nav>

            <div v-if="isRecoveryCodesVisible" class="mt-3 space-y-3">
                <div
                    ref="recoveryCodeSectionRef"
                    class="grid gap-2 rounded-lg bg-muted/50 p-4 font-mono text-sm"
                >
                    <template v-if="!recoveryCodesList.length">
                        <Skeleton v-for="n in 8" :key="n" class="h-5 w-1/2" />
                    </template>
                    <template v-else>
                        <p v-for="code in recoveryCodesList" :key="code">
                            {{ code }}
                        </p>
                    </template>
                </div>

                <p class="text-xs text-muted-foreground select-none">
                    Each recovery code can be used once to access your account
                    and will be removed after use. If you need more, click
                    <span class="font-bold">Regenerate Codes</span> above.
                </p>
            </div>
        </CardContent>
    </Card>
</template>
