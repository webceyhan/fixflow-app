<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import EditButton from "@/Components/EditButton.vue";

defineProps<{
  customer: any;
}>();
</script>

<template>
  <Head :title="`Customer ${customer.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Customer {{ customer.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <pre>{{ customer }}</pre>

        <div class="flex gap-4 my-4">
          <EditButton :href="route('customers.edit', customer.id)" />
          <DeleteButton :href="route('customers.destroy', customer.id)" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
