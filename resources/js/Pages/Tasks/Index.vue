<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Menu from "@/Components/Menu.vue";
import MenuLink from "@/Components/MenuLink.vue";
import CreateButton from "@/Components/CreateButton.vue";

defineProps<{
  tasks: any[];
}>();
</script>

<template>
  <Head title="Tasks" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl leading-tight">Tasks</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <CreateButton :href="route('tasks.create')" />

        <Menu>
          <MenuLink v-for="task of tasks" :href="route('tasks.show', task.id)">
            {{ task.description }}
          </MenuLink>
        </Menu>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
