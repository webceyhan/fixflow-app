<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import MenuLink from "@/Components/MenuLink.vue";
import Menu from "@/Components/Menu.vue";
import Navbar from "@/Components/Navbar.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      <Navbar class="gap-2 sm:px-4">
        <!-- Logo -->
        <div class="max-sm:flex-1">
          <Link :href="route('dashboard')">
            <ApplicationLogo class="block h-9 w-auto fill-current px-2" />
          </Link>
        </div>

        <!-- Navigation Links -->
        <div class="max-sm:hidden flex-1">
          <Menu horizontal>
            <MenuLink :href="route('dashboard')" :active="route().current('dashboard')">
              Dashboard
            </MenuLink>
          </Menu>
        </div>

        <!-- Settings Dropdown -->
        <div class="max-sm:hidden flex-none">
          <Dropdown align-end :label="$page.props.auth.user.name">
            <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
            <DropdownLink :href="route('logout')" method="post"> Log Out </DropdownLink>
          </Dropdown>
        </div>

        <!-- Hamburger -->
        <div class="flex sm:hidden">
          <button
            @click="showingNavigationDropdown = !showingNavigationDropdown"
            class="btn"
          >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path
                :class="{
                  hidden: showingNavigationDropdown,
                  'inline-flex': !showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                :class="{
                  hidden: !showingNavigationDropdown,
                  'inline-flex': showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </Navbar>

      <!-- Responsive Navigation Menu -->
      <Navbar v-if="showingNavigationDropdown" class="sm:hidden">
        <Menu class="flex-1 w-full">
          <MenuLink
            :href="route('dashboard')"
            :active="route().current('dashboard')"
            class="w-full"
          >
            Dashboard
          </MenuLink>

          <!-- Responsive Settings Options -->
          <div class="w-full pt-4 px-4">
            <div class="font-medium text-base text-gray-800">
              {{ $page.props.auth.user.name }}
            </div>
            <div class="font-medium text-sm text-gray-500">
              {{ $page.props.auth.user.email }}
            </div>
          </div>

          <li class="w-full">
            <ul class="w-full">
              <MenuLink :href="route('profile.edit')"> Profile </MenuLink>
              <MenuLink :href="route('logout')" method="post" as="button">
                Log Out
              </MenuLink>
            </ul>
          </li>
        </Menu>
      </Navbar>

      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
