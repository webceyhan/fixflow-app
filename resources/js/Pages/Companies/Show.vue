<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import EditButton from "@/Components/EditButton.vue";

defineProps<{
  company: any;
}>();
</script>

<template>
  <Head :title="`Company ${company.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Company {{ company.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <pre>{{ company }}</pre>

        <div class="flex gap-4 my-4">
          <EditButton :href="route('companies.edit', company.id)" />
          <DeleteButton :href="route('companies.destroy', company.id)" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
