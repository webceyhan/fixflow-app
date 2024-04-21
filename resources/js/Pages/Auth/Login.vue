<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Link from "@/Components/Link.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextInput from "@/Components/TextInput.vue";
import FormControl from "@/Components/FormControl.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

defineProps<{
  canResetPassword?: boolean;
  status?: string;
}>();

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => {
      form.reset("password");
    },
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Log in" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <FormControl label="Email" :error="form.errors.email">
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />
        </FormControl>
      </div>

      <div class="mt-4">
        <FormControl label="Password" :error="form.errors.password">
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
        </FormControl>
      </div>

      <div class="block mt-4">
        <FormControl label="Remember me" inline>
          <Checkbox name="remember" v-model:checked="form.remember" />
        </FormControl>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          label="Forgot your password?"
        />

        <PrimaryButton class="ms-4" label="Log in" :disabled="form.processing" />
      </div>
    </form>
  </GuestLayout>
</template>
