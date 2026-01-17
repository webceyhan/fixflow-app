<script setup lang="ts">
import AppLogo from '@/components/app/AppLogo.vue';
import { Toaster } from '@/components/ui/sonner';
import { useToast } from '@/composables/useToast';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

useToast().watchStatus();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <div
            class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r"
        >
            <div class="absolute inset-0 bg-zinc-900">
                <img
                    src="/auth-splash.jpg"
                    alt="Authentication background"
                    class="h-full w-full object-cover opacity-80"
                />
            </div>

            <Link :href="home()" class="relative z-20 flex items-center">
                <AppLogo class="origin-left scale-150" />
            </Link>
        </div>
        <div class="lg:p-8">
            <Toaster />
            <div class="mx-auto space-y-10! sm:w-87.5 [&>header]:text-center">
                <slot />
            </div>
        </div>
    </div>
</template>
