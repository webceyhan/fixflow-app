<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import FormControl from "@/Components/Form/FormControl.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const form = useForm({
  password: "",
});

const submit = () => {
  form.post(route("password.confirm"), {
    onFinish: () => {
      form.reset();
    },
  });
};
</script>

<template>
  <GuestLayout>
    <Head title="Confirm Password" />

    <div class="mb-4 text-sm text-gray-600">
      This is a secure area of the application. Please confirm your password before
      continuing.
    </div>

    <form @submit.prevent="submit">
      <div>
        <FormControl label="Password" :error="form.errors.password">
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="current-password"
            autofocus
          />
        </FormControl>
      </div>

      <div class="flex justify-end mt-4">
        <PrimaryButton class="ms-4" label="Confirm" :disabled="form.processing" />
      </div>
    </form>
  </GuestLayout>
</template>
