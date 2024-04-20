<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps<{
  ticket: any;
}>();
</script>

<template>
  <Head :title="`Ticket ${ticket.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Ticket {{ ticket.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <pre>{{ ticket }}</pre>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
