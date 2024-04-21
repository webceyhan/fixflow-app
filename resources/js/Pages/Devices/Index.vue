<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Menu from "@/Components/Menu.vue";
import MenuLink from "@/Components/MenuLink.vue";
import CreateButton from "@/Components/CreateButton.vue";

defineProps<{
  devices: any[];
}>();
</script>

<template>
  <Head title="Devices" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Devices</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <CreateButton :href="route('devices.create')" />

        <Menu>
          <MenuLink v-for="device of devices" :href="route('devices.show', device.id)">
            {{ device.model }}
          </MenuLink>
        </Menu>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
