<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import EditButton from "@/Components/EditButton.vue";

defineProps<{
  invoice: any;
}>();
</script>

<template>
  <Head :title="`Invoice ${invoice.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Invoice {{ invoice.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <pre>{{ invoice }}</pre>

        <div class="flex gap-4 my-4">
          <EditButton :href="route('invoices.edit', invoice.id)" />
          <DeleteButton :href="route('invoices.destroy', invoice.id)" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
