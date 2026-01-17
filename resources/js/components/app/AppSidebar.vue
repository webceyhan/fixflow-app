<script setup lang="ts">
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { Link } from '@inertiajs/vue3';
import AppLogo from './AppLogo.vue';
import NavFooter from './nav/NavFooter.vue';
import NavMain from './nav/NavMain.vue';
import NavUser from './nav/NavUser.vue';
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
