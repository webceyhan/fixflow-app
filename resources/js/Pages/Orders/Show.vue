<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteButton from "@/Components/DeleteButton.vue";
import EditButton from "@/Components/EditButton.vue";

defineProps<{
  order: any;
}>();
</script>

<template>
  <Head :title="`Order ${order.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Order {{ order.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <pre>{{ order }}</pre>

        <div class="flex gap-4 my-4">
          <EditButton :href="route('orders.edit', order.id)" />
          <DeleteButton :href="route('orders.destroy', order.id)" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
