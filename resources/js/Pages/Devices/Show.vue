<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps<{
  device: any;
}>();
</script>

<template>
  <Head :title="`Device ${device.id}`" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Device {{ device.id }}</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <pre>{{ device }}</pre>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
