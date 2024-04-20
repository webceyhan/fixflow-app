<script setup lang="ts">
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Card from "@/Components/Card.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div>
      <Link href="/">
        <ApplicationLogo class="w-20 h-20 fill-current" />
      </Link>
    </div>

    <Card class="w-full sm:max-w-md mt-6">
      <slot />
    </Card>
  </div>
</template>
