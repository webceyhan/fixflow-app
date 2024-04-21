<script setup lang="ts">
import Link from "@/Components/Link.vue";
import Navbar from "@/Components/Navbar.vue";
import Drawer from "@/Components/Drawer.vue";
import Menu from "@/Components/Menu.vue";
import MenuLink from "@/Components/MenuLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Icon from "@/Components/Icon.vue";
</script>

<template>
  <div>
    <div class="min-h-screen">
      <Navbar class="gap-2 sm:px-4">
        <!-- Logo -->
        <div class="flex-1">
          <Link :href="route('dashboard')">
            <Icon name="logo" class="w-16 h-10 px-2" />
          </Link>
        </div>

        <!-- Settings Dropdown -->
        <div class="max-sm:hidden flex-none">
          <Dropdown align-end :label="$page.props.auth.user.name">
            <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
            <DropdownLink :href="route('logout')" method="post"> Log Out </DropdownLink>
          </Dropdown>
        </div>

        <!-- Sidebar Toggle -->
        <label for="sidebar" class="btn drawer-button lg:hidden">
          <Icon name="menu" class="h-6 w-6" />
        </label>
      </Navbar>

      <Drawer id="sidebar">
        <template #aside>
          <!-- Navigation Links -->
          <Menu class="mb-auto">
            <MenuLink
              label="Dashboard"
              :href="route('dashboard')"
              :active="route().current('dashboard')"
            />
          </Menu>

          <!-- Responsive Settings Options -->
          <div class="sm:hidden divider" />

          <Menu class="sm:hidden">
            <li>
              <h2 class="menu-title">
                <p class="text-base text-base-content">
                  {{ $page.props.auth.user.name }}
                </p>
                {{ $page.props.auth.user.email }}
              </h2>

              <ul>
                <MenuLink label="Profile" :href="route('profile.edit')" />
                <MenuLink label="Log Out" :href="route('logout')" method="post" />
              </ul>
            </li>
          </Menu>
        </template>

        <!-- Page Heading -->
        <header class="bg-neutral text-neutral-content shadow" v-if="$slots.header">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header" />
          </div>
        </header>

        <!-- Page Content -->
        <main>
          <slot />
        </main>
      </Drawer>
    </div>
  </div>
</template>
