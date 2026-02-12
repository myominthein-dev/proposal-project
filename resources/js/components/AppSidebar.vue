<script setup >
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Calendar, Folder, LayoutGrid } from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
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
import AppLogo from './AppLogo.vue';
import { computed, onMounted, watch } from 'vue';

const page = usePage();

    


const mainNavItems = [
    {
        title: 'Appointments',
        href: '/appointments',
        icon: Calendar,
    },
];



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
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
           
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
