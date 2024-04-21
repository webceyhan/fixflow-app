<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Link from "@/Components/Link.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const props = defineProps<{
  status?: string;
}>();

const form = useForm({});

const submit = () => {
  form.post(route("verification.send"));
};

const verificationLinkSent = computed(() => props.status === "verification-link-sent");
</script>

<template>
  <GuestLayout>
    <Head title="Email Verification" />

    <div class="mb-4 text-sm text-gray-600">
      Thanks for signing up! Before getting started, could you verify your email address
      by clicking on the link we just emailed to you? If you didn't receive the email, we
      will gladly send you another.
    </div>

    <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
      A new verification link has been sent to the email address you provided during
      registration.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <PrimaryButton label="Resend Verification Email" :disabled="form.processing" />

        <Link :href="route('logout')" method="post" as="button" label="Log Out" />
      </div>
    </form>
  </GuestLayout>
</template>
