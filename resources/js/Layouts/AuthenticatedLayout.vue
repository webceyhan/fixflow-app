<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import MenuLink from "@/Components/MenuLink.vue";
import Menu from "@/Components/Menu.vue";
import Navbar from "@/Components/Navbar.vue";
import Drawer from "@/Components/Drawer.vue";
</script>

<template>
  <div>
    <div class="min-h-screen">
      <Navbar class="gap-2 sm:px-4">
        <!-- Logo -->
        <div class="flex-1">
          <Link :href="route('dashboard')">
            <ApplicationLogo class="block h-9 w-auto fill-current px-2" />
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
          <!-- hamburger icon -->
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16"
            />
          </svg>
          <!-- close icon -->
          <!-- <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg> -->
        </label>
      </Navbar>

      <Drawer id="sidebar">
        <template #aside>
          <!-- Navigation Links -->
          <Menu class="mb-auto">
            <MenuLink :href="route('dashboard')" :active="route().current('dashboard')">
              Dashboard
            </MenuLink>
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
                <MenuLink :href="route('profile.edit')"> Profile </MenuLink>
                <MenuLink :href="route('logout')" method="post" as="button">
                  Log Out
                </MenuLink>
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
