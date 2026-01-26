<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Briefcase, Calendar, Clock, TrendingUp, Users } from 'lucide-vue-next';

interface Stats {
    total_jobs: number;
    applicants: {
        weekly: number;
        biweekly: number;
        monthly: number;
        total: number;
    };
    upcoming_appointments: number;
}

defineProps<{
    stats: Stats;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4 pt-0">
            <!-- KPIs Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium">
                            Total Job Postings
                        </CardTitle>
                        <Briefcase class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ stats.total_jobs }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            Active positions
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium">
                            Total Applicants
                        </CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ stats.applicants.total }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            All time applications
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium">
                            New Applicants (Weekly)
                        </CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ stats.applicants.weekly }}
                        </div>
                        <p class="text-xs text-muted-foreground">Last 7 days</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between space-y-0 pb-2"
                    >
                        <CardTitle class="text-sm font-medium">
                            New Applicants (Monthly)
                        </CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ stats.applicants.monthly }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            Last 30 days
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <!-- Recent Activity / Applicants Stats Breakdown -->
                <Card class="col-span-4">
                    <CardHeader>
                        <CardTitle>Application Velocity</CardTitle>
                        <CardDescription>
                            Overview of applicant intake over different periods.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-8">
                            <div class="flex items-center">
                                <div class="ml-4 space-y-1">
                                    <p class="text-sm leading-none font-medium">
                                        Weekly
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Last 7 days
                                    </p>
                                </div>
                                <div class="ml-auto font-medium">
                                    {{ stats.applicants.weekly }} Applicants
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4 space-y-1">
                                    <p class="text-sm leading-none font-medium">
                                        Biweekly
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Last 14 days
                                    </p>
                                </div>
                                <div class="ml-auto font-medium">
                                    {{ stats.applicants.biweekly }} Applicants
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="ml-4 space-y-1">
                                    <p class="text-sm leading-none font-medium">
                                        Monthly
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Last 30 days
                                    </p>
                                </div>
                                <div class="ml-auto font-medium">
                                    {{ stats.applicants.monthly }} Applicants
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Upcoming Appointments -->
                <Card class="col-span-3">
                    <CardHeader>
                        <CardTitle>Upcoming Appointments</CardTitle>
                        <CardDescription>
                            Scheduled interviews with candidates.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="stats.upcoming_appointments > 0"
                            class="space-y-4"
                        >
                            <!-- Placeholder for actual list -->
                            <div class="flex items-center">
                                <Clock class="h-9 w-9 text-muted-foreground" />
                                <div class="ml-4 space-y-1">
                                    <p class="text-sm leading-none font-medium">
                                        Mock Interview
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Tomorrow at 10:00 AM
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            v-else
                            class="flex h-[200px] flex-col items-center justify-center space-y-2 text-center"
                        >
                            <Calendar
                                class="h-10 w-10 text-muted-foreground/50"
                            />
                            <p class="text-sm text-muted-foreground">
                                No upcoming appointments.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
