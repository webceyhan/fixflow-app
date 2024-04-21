<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteButton from "@/Components/DeleteButton.vue";

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

        <br />

        <DeleteButton :href="route('orders.destroy', order.id)" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
