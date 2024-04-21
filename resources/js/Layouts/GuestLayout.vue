<script setup lang="ts">
import Link from "@/Components/Link.vue";
import Card from "@/Components/Card.vue";
import Icon from "@/Components/Icon.vue";
</script>

<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div>
      <Link href="/">
        <Icon name="logo" class="w-20 h-20" />
      </Link>
    </div>

    <Card class="w-full sm:max-w-md mt-6">
      <slot />
    </Card>
  </div>
</template>
