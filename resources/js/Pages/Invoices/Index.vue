<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Menu from "@/Components/Menu.vue";
import MenuLink from "@/Components/MenuLink.vue";

defineProps<{
  invoices: any[];
}>();
</script>

<template>
  <Head title="Invoices" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Invoices</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Menu>
          <MenuLink v-for="invoice of invoices" :href="route('invoices.show', invoice.id)">
            #{{ invoice.id }} - ${{ invoice.total }}
          </MenuLink>
        </Menu>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
