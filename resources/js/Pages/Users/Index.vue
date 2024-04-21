<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Menu from "@/Components/Menu.vue";
import MenuLink from "@/Components/MenuLink.vue";
import CreateButton from "@/Components/CreateButton.vue";

defineProps<{
  users: any[];
}>();
</script>

<template>
  <Head title="Users" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Users</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <CreateButton :href="route('users.create')" />

        <Menu>
          <MenuLink v-for="user of users" :href="route('users.show', user.id)">
            {{ user.name }}
          </MenuLink>
        </Menu>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
