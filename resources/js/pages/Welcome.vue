<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import AppLogo from '@/components/app/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { dashboard, login, register } from '@/routes';

defineProps<{
    canRegister: boolean;
}>();
</script>

<template>
    <Head title="Welcome" />
    <div
        class="flex min-h-screen flex-col items-center p-6 lg:justify-center lg:p-8"
    >
        <header
            class="mb-6 w-full max-w-83.75 text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end">
                <Button v-if="$page.props.auth.user" variant="outline" as-child>
                    <Link :href="dashboard()"> Dashboard </Link>
                </Button>

                <template v-else>
                    <Button variant="ghost" as-child>
                        <Link :href="login()"> Log in </Link>
                    </Button>

                    <Button v-if="canRegister" variant="ghost" as-child>
                        <Link :href="register()"> Register </Link>
                    </Button>
                </template>
            </nav>
        </header>
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <Card
                class="relative w-full overflow-hidden rounded-4xl p-0 lg:max-w-4xl"
            >
                <div class="absolute inset-0">
                    <img
                        src="/auth-splash.jpg"
                        alt="Authentication background"
                        class="h-full w-full object-cover opacity-80"
                    />
                </div>

                <CardHeader class="p-8 lg:p-24">
                    <AppLogo class="origin-top-left scale-200" />
                </CardHeader>

                <CardContent
                    class="z-10 rounded-tr-4xl bg-black/40 p-8 lg:max-w-3/4 lg:p-24 lg:text-xl"
                >
                    Welcome to Fixflow, Repair Shop Management System! Your
                    workflow automation starts here.

                    <p class="mt-8 text-sm text-muted-foreground">
                        Please log in or register to continue.
                    </p>
                </CardContent>
            </Card>
        </div>
        <div class="hidden h-14.5 lg:block" />
    </div>
</template>
