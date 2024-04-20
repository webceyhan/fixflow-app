<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import MenuLink from "@/Components/MenuLink.vue";
import Menu from "@/Components/Menu.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')">
                  <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <Menu class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <MenuLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  Dashboard
                </MenuLink>
              </Menu>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
              <!-- Settings Dropdown -->
              <div class="ms-3 relative">
                <Dropdown align-end :label="$page.props.auth.user.name">
                  <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                  <DropdownLink :href="route('logout')" method="post">
                    Log Out
                  </DropdownLink>
                </Dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
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
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <Menu
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
          <MenuLink :href="route('dashboard')" :active="route().current('dashboard')">
            Dashboard
          </MenuLink>

          <!-- Responsive Settings Options -->
          <div class="pt-4 px-4">
            <div class="font-medium text-base text-gray-800">
              {{ $page.props.auth.user.name }}
            </div>
            <div class="font-medium text-sm text-gray-500">
              {{ $page.props.auth.user.email }}
            </div>
          </div>

          <li>
            <ul>
              <MenuLink :href="route('profile.edit')"> Profile </MenuLink>
              <MenuLink :href="route('logout')" method="post" as="button">
                Log Out
              </MenuLink>
            </ul>
          </li>
        </Menu>
      </nav>

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
