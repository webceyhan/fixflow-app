<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import Link from "@/Components/Link.vue";
import Icon from "@/Components/Icon.vue";

defineProps<{
  canLogin?: boolean;
  canRegister?: boolean;
  laravelVersion: string;
  phpVersion: string;
}>();

function handleImageError() {
  document.getElementById("screenshot-container")?.classList.add("!hidden");
  document.getElementById("docs-card")?.classList.add("!row-span-1");
  document.getElementById("docs-card-content")?.classList.add("!flex-row");
  document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
  <Head title="Welcome" />
  <div>
    <img
      id="background"
      class="absolute -left-20 top-0 max-w-[877px]"
      src="https://laravel.com/assets/img/welcome/background.svg"
    />
    <div class="relative min-h-screen flex flex-col items-center justify-center">
      <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
          <div class="flex lg:justify-center lg:col-start-2">
            <Icon name="logo" class="h-12 w-auto lg:h-16" />
          </div>
          <nav v-if="canLogin" class="flex flex-1 justify-end gap-6">
            <Link
              v-if="$page.props.auth.user"
              :href="route('dashboard')"
              label="Dashboard"
            />

            <template v-else>
              <Link :href="route('login')" label="Log in" />

              <Link v-if="canRegister" :href="route('register')" label="Register" />
            </template>
          </nav>
        </header>

        <main class="mt-6">
          <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
            <a
              href="https://laravel.com/docs"
              id="docs-card"
              class="flex flex-col items-start gap-6 overflow-hidden rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 transition duration-300 focus:outline-none md:row-span-3 lg:p-10 lg:pb-10"
            >
              <div
                id="screenshot-container"
                class="relative flex w-full flex-1 items-stretch"
              >
                <img
                  src="https://laravel.com/assets/img/welcome/docs-light.svg"
                  alt="Laravel documentation screenshot"
                  class="aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.06)]hidden"
                  @error="handleImageError"
                />
                <img
                  src="https://laravel.com/assets/img/welcome/docs-dark.svg"
                  alt="Laravel documentation screenshot"
                  class="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-top object-cover drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)]block"
                />
                <div class="absolute -bottom-16 -left-16 h-40 w-[calc(100%+8rem)]"></div>
              </div>

              <div class="relative flex items-center gap-6 lg:items-end">
                <div id="docs-card-content" class="flex items-start gap-6 lg:flex-col">
                  <div
                    class="flex size-12 shrink-0 items-center justify-center rounded-full sm:size-16"
                  >
                    <Icon name="docs" class="size-5 sm:size-6 text-accent" />
                  </div>

                  <div class="pt-3 sm:pt-5 lg:pt-0">
                    <h2 class="text-xl font-semibold">Documentation</h2>

                    <p class="mt-4 text-sm/relaxed">
                      Laravel has wonderful documentation covering every aspect of the
                      framework. Whether you are a newcomer or have prior experience with
                      Laravel, we recommend reading our documentation from beginning to
                      end.
                    </p>
                  </div>
                </div>

                <Icon name="arrow-right" class="size-6 shrink-0 stroke-accent" />
              </div>
            </a>

            <a
              href="https://laracasts.com"
              class="flex items-start gap-4 rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 transition duration-300 focus:outline-none lg:pb-10"
            >
              <div
                class="flex size-12 shrink-0 items-center justify-center rounded-full sm:size-16"
              >
                <Icon name="videos" class="size-5 sm:size-6 text-accent" />
              </div>

              <div class="pt-3 sm:pt-5">
                <h2 class="text-xl font-semibold">Laracasts</h2>

                <p class="mt-4 text-sm/relaxed">
                  Laracasts offers thousands of video tutorials on Laravel, PHP, and
                  JavaScript development. Check them out, see for yourself, and massively
                  level up your development skills in the process.
                </p>
              </div>

              <Icon name="arrow-right" class="size-6 shrink-0 stroke-accent" />
            </a>

            <a
              href="https://laravel-news.com"
              class="flex items-start gap-4 rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 transition duration-300 focus:outline-none lg:pb-10"
            >
              <div
                class="flex size-12 shrink-0 items-center justify-center rounded-full sm:size-16"
              >
                <Icon name="news" class="size-5 sm:size-6 text-accent" />
              </div>

              <div class="pt-3 sm:pt-5">
                <h2 class="text-xl font-semibold">Laravel News</h2>

                <p class="mt-4 text-sm/relaxed">
                  Laravel News is a community driven portal and newsletter aggregating all
                  of the latest and most important news in the Laravel ecosystem,
                  including new package releases and tutorials.
                </p>
              </div>

              <Icon name="arrow-right" class="size-6 shrink-0 stroke-accent" />
            </a>

            <div
              class="flex items-start gap-4 rounded-lg p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 lg:pb-10"
            >
              <div
                class="flex size-12 shrink-0 items-center justify-center rounded-full sm:size-16"
              >
                <Icon name="community" class="size-5 sm:size-6 text-accent" />
              </div>

              <div class="pt-3 sm:pt-5">
                <h2 class="text-xl font-semibold">Vibrant Ecosystem</h2>

                <p class="mt-4 text-sm/relaxed">
                  Laravel's robust library of first-party tools and libraries, such as
                  <a
                    href="https://forge.laravel.com"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Forge</a
                  >,
                  <a
                    href="https://vapor.laravel.com"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Vapor</a
                  >,
                  <a
                    href="https://nova.laravel.com"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Nova</a
                  >,
                  <a
                    href="https://envoyer.io"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Envoyer</a
                  >, and
                  <a
                    href="https://herd.laravel.com"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Herd</a
                  >
                  help you take your projects to the next level. Pair them with powerful
                  open source libraries like
                  <a
                    href="https://laravel.com/docs/billing"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Cashier</a
                  >,
                  <a
                    href="https://laravel.com/docs/dusk"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Dusk</a
                  >,
                  <a
                    href="https://laravel.com/docs/broadcasting"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Echo</a
                  >,
                  <a
                    href="https://laravel.com/docs/horizon"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Horizon</a
                  >,
                  <a
                    href="https://laravel.com/docs/sanctum"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Sanctum</a
                  >,
                  <a
                    href="https://laravel.com/docs/telescope"
                    class="rounded-sm underline focus:outline-none focus-visible:ring-1"
                    >Telescope</a
                  >, and more.
                </p>
              </div>
            </div>
          </div>
        </main>

        <footer class="py-16 text-center text-sm /70">
          Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer>
      </div>
    </div>
  </div>
</template>
