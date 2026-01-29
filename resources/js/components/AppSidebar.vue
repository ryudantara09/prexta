<script setup lang="ts">
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
import { dashboard, home } from '@/routes';
import { index as applicationsIndex } from '@/routes/dashboard/applications';
import {
    index as interviewsIndex,
    pending as interviewsPending,
} from '@/routes/dashboard/interviews';
import { index as jobPositionsIndex } from '@/routes/dashboard/job-positions';
import { index as publicJobsIndex } from '@/routes/jobs';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    Activity,
    Briefcase,
    Calendar,
    Folder,
    LayoutGrid,
    Link as LinkIcon,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import GeneralBookingButton from './GeneralBookingButton.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Job Positions',
        href: jobPositionsIndex(),
        icon: Briefcase,
    },
    {
        title: 'Applications',
        href: applicationsIndex(),
        icon: Folder,
    },
    {
        title: 'Interviews & Calendar',
        href: interviewsIndex(),
        icon: Calendar,
    },
    {
        title: 'Pending Links',
        href: interviewsPending(),
        icon: LinkIcon,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Home Page',
        href: home(),
        icon: Activity,
    },
    {
        title: 'Published Jobs',
        href: publicJobsIndex(),
        icon: Briefcase,
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
            <div class="px-4 py-2">
                <GeneralBookingButton
                    variant="secondary"
                    size="sm"
                    class="w-full justify-start"
                />
            </div>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
