<script setup lang="ts">
import DangerButton from "@/Components/Button/DangerButton.vue";
import FormControl from "@/Components/FormControl.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
  password: "",
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
  form.delete(route("profile.destroy"), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value?.focus(),
    onFinish: () => {
      form.reset();
    },
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
};
</script>

<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-medium">Delete Account</h2>

      <p class="mt-1 text-sm">
        Once your account is deleted, all of its resources and data will be permanently
        deleted. Before deleting your account, please download any data or information
        that you wish to retain.
      </p>
    </header>

    <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

    <Modal v-model:open="confirmingUserDeletion">
      <template #title> Are you sure you want to delete your account? </template>

      <p class="text-sm">
        Once your account is deleted, all of its resources and data will be permanently
        deleted. Please enter your password to confirm you would like to permanently
        delete your account.
      </p>

      <FormControl label="Password" :error="form.errors.password">
        <TextInput
          ref="passwordInput"
          type="password"
          placeholder="Password"
          v-model="form.password"
          @keyup.enter="deleteUser"
        />
      </FormControl>

      <template #actions>
        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

        <DangerButton :disabled="form.processing" @click="deleteUser">
          Delete Account
        </DangerButton>
      </template>
    </Modal>
  </section>
</template>
