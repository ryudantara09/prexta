<script setup lang="ts">
import SchedulingDialog from '@/components/SchedulingDialog.vue';
import { Button } from '@/components/ui/button';
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
import { Head, router } from '@inertiajs/vue3';
import { Briefcase, Calendar, Clock, TrendingUp, Users } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { ref } from 'vue';

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
    recent_applications: Array<{
        id: number;
        applicant: { full_name: string; email: string };
        job_position: { title: string };
        created_at: string;
        interview: {
            id: number;
            token: string;
            status: string;
            scheduled_at: string | null;
        } | null;
    }>;
}>();

const schedulingDialogOpen = ref(false);
const schedulingApplicationId = ref<number | null>(null);
const schedulingApplicantName = ref('');

const openSchedulingDialog = (app: any) => {
    schedulingApplicationId.value = app.id;
    schedulingApplicantName.value = app.applicant.full_name;
    schedulingDialogOpen.value = true;
};

const generateInterviewLink = (applicationId: number) => {
    router.post(
        `/dashboard/applications/${applicationId}/interview`,
        {},
        {
            preserveScroll: true,
            onSuccess: (page) => {
                const flash = page.props.flash as any;
                if (flash && flash.data && flash.data.interview_url) {
                    navigator.clipboard.writeText(flash.data.interview_url);
                    toast.success(flash.message, {
                        description: flash.data.interview_url,
                        duration: 8000,
                    });
                } else {
                    console.error('Flash data missing or incorrect:', flash);
                    toast.error(
                        'Process completed, but could not find the link.',
                    );
                }
            },
        },
    );
};

const copyInterviewLink = (token: string) => {
    const url = `${window.location.origin}/interview/${token}`;
    navigator.clipboard.writeText(url);
    toast.success('Interview link copied to clipboard!');
};

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
                        <CardTitle>Recent Applications</CardTitle>
                        <CardDescription>
                            Latest candidates who applied.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div
                                v-for="app in recent_applications"
                                :key="app.id"
                                class="flex items-center justify-between border-b pb-4 last:border-0 last:pb-0"
                            >
                                <div>
                                    <p class="text-sm leading-none font-medium">
                                        {{ app.applicant.full_name }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        {{ app.job_position.title }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div
                                        v-if="app.interview"
                                        class="flex flex-col items-end gap-1"
                                    >
                                        <span
                                            class="text-[10px] font-bold uppercase"
                                            :class="{
                                                'text-yellow-600':
                                                    app.interview.status ===
                                                    'pending',
                                                'text-green-600':
                                                    app.interview.status ===
                                                    'scheduled',
                                                'text-red-600':
                                                    app.interview.status ===
                                                    'cancelled',
                                                'text-blue-600':
                                                    app.interview.status ===
                                                    'completed',
                                            }"
                                        >
                                            {{ app.interview.status }}
                                        </span>
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            class="h-6 px-2 text-[10px]"
                                            @click="
                                                copyInterviewLink(
                                                    app.interview.token,
                                                )
                                            "
                                        >
                                            Copy Link
                                        </Button>
                                    </div>
                                    <Button
                                        v-else
                                        size="sm"
                                        variant="secondary"
                                        class="h-7 px-2 text-xs"
                                        @click="openSchedulingDialog(app)"
                                    >
                                        Schedule
                                    </Button>
                                </div>
                            </div>
                            <div
                                v-if="recent_applications.length === 0"
                                class="py-4 text-center text-sm text-muted-foreground"
                            >
                                No recent applications.
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

        <SchedulingDialog
            v-model:is-open="schedulingDialogOpen"
            :application-id="schedulingApplicationId"
            :applicant-name="schedulingApplicantName"
            @link-generated="generateInterviewLink"
        />
    </AppLayout>
</template>
