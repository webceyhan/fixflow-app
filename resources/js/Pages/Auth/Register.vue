<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import Link from "@/Components/Link.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import FormControl from "@/Components/Form/FormControl.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => {
      form.reset("password", "password_confirmation");
    },
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <form @submit.prevent="submit">
      <div>
        <FormControl label="Name" :error="form.errors.name">
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
        </FormControl>
      </div>

      <div class="mt-4">
        <FormControl label="Email" :error="form.errors.email">
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
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
        <Link :href="route('login')" label="Already registered?" />

        <PrimaryButton class="ms-4" label="Register" :disabled="form.processing" />
      </div>
    </form>
  </GuestLayout>
</template>
