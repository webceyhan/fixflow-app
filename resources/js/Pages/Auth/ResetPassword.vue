<script setup lang="ts">
import GuestLayout from "@/Layouts/GuestLayout.vue";
import FormControl from "@/Components/FormControl.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps<{
  email: string;
  token: string;
}>();

const form = useForm({
  token: props.token,
  email: props.email,
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("password.store"), {
    onFinish: () => {
      form.reset("password", "password_confirmation");
    },
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Reset Password" />

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
            autocomplete="new-password"
          />
        </FormControl>
      </div>

      <div class="mt-4">
        <FormControl label="Confirm Password" :error="form.errors.password_confirmation">
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />
        </FormControl>
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :disabled="form.processing"> Reset Password </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
