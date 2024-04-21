<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Menu from "@/Components/Menu.vue";
import MenuLink from "@/Components/MenuLink.vue";
import CreateButton from "@/Components/CreateButton.vue";

defineProps<{
  orders: any[];
}>();
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Orders</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <CreateButton :href="route('orders.create')" />

        <Menu>
          <MenuLink v-for="order of orders" :href="route('orders.show', order.id)">
            {{ order.name }}
          </MenuLink>
        </Menu>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
