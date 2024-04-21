<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import FormControl from "@/Components/FormControl.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps<{
  user: any;
}>();

const isNew = props.user?.id === undefined;
const action = isNew ? "Create" : "Update";

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  phone: props.user.phone,
});

const save = () => {
  isNew // should call store or update?
    ? form.post(route("users.store"))
    : form.put(route("users.update", props.user.id));
};
</script>

<template>
  <Head :title="`${action} User ${user.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">{{ action }} User {{ user.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form @submit.prevent="save" class="space-y-4">
          <FormControl v-for="(value, field) in form.data()" :label="field" :error="form.errors[field]">
            <TextInput v-model="form[field]" />
          </FormControl>

          <PrimaryButton type="submit">{{ action }}</PrimaryButton>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
