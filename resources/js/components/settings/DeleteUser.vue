<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { destroy } from '@/routes/profile';
import { Form } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';

const passwordInput = useTemplateRef('passwordInput');
</script>

<template>
    <FieldSet>
        <FieldLegend>Delete account</FieldLegend>
        <FieldDescription>
            Delete your account and all of its resources
        </FieldDescription>

        <div
            class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10"
        >
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">Warning</p>
                <p class="text-sm">
                    Please proceed with caution, this cannot be undone.
                </p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button
                        variant="destructive"
                        data-test="delete-user-button"
                    >
                        Delete account
                    </Button>
                </DialogTrigger>

                <DialogContent>
                    <Form
                        class="space-y-4"
                        v-bind="destroy.form()"
                        v-slot="{ errors, processing, reset, clearErrors }"
                        :options="{ preserveScroll: true }"
                        reset-on-success
                        @error="() => passwordInput?.$el?.focus()"
                    >
                        <DialogHeader>
                            <DialogTitle>
                                Are you sure you want to delete your account?
                            </DialogTitle>
                            <DialogDescription>
                                Once your account is deleted, all of its
                                resources and data will also be permanently
                                deleted. Please enter your password to confirm
                                you would like to permanently delete your
                                account.
                            </DialogDescription>
                        </DialogHeader>

                        <FieldSet>
                            <Field>
                                <FieldLabel for="password" class="sr-only">
                                    Password
                                </FieldLabel>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    ref="passwordInput"
                                    placeholder="Password"
                                />
                                <FieldError :message="errors.password" />
                            </Field>
                        </FieldSet>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button
                                    variant="secondary"
                                    @click="
                                        () => {
                                            clearErrors();
                                            reset();
                                        }
                                    "
                                >
                                    Cancel
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                variant="destructive"
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                            >
                                Delete account
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </FieldSet>
</template>
