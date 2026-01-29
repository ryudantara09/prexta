<script setup lang="ts">
import CalendarSchedulingDialog from '@/components/CalendarSchedulingDialog.vue';
import GeneralBookingButton from '@/components/GeneralBookingButton.vue';
import InterviewDetailsDialog from '@/components/InterviewDetailsDialog.vue';
import DragRescheduleConfirmationDialog from '@/components/DragRescheduleConfirmationDialog.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    events: Array<any>;
    applications: Array<{ id: number; name: string }>;
    job_positions: Array<{ id: number; title: string }>;
}>();

const isDialogOpen = ref(false);
const isDetailsDialogOpen = ref(false);
const isRescheduleConfirmOpen = ref(false);
const selectedEvent = ref<any>(null);
const selectedDate = ref<string>('');
const pendingDropInfo = ref<any>(null); // To store valid drop info for confirmation

const filters = ref({
    jobPositionId: '' as string | number,
    status: '' as string,
});

const filteredEvents = computed(() => {
    return props.events.filter((event) => {
        let matchesJob = true;
        let matchesStatus = true;

        if (filters.value.jobPositionId) {
            // Check if event job matches
            // Note: event.extendedProps.job is a string title currently in controller.
            // Ideally we need job_id in extendedProps. 
            // Let's assume matching by string title for now or we should have passed ID.
            // Wait, I passed 'job' as title in Controller. I should have passed job_id or full object.
            // Let's match by title if I can't change controller easily again, OR better:
            // Since filters.value.jobPositionId is an ID, I need to match against ID.
            // I'll update the logic to match 'job' title against the selected job title.
             const selectedJob = props.job_positions.find(j => j.id == filters.value.jobPositionId);
             if (selectedJob) {
                 matchesJob = event.extendedProps.job === selectedJob.title;
             }
        }

        if (filters.value.status) {
            matchesStatus = event.extendedProps.status === filters.value.status;
        }

        return matchesJob && matchesStatus;
    });
});


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Interviews',
        href: '/dashboard/interviews',
    },
];

const calendarOptions = computed(() => ({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
    },
    events: filteredEvents.value,
    editable: true,
    selectable: true,
    selectMirror: true,
    dayMaxEvents: true,
    weekends: true,
    nowIndicator: true,
    businessHours: {
        // days of week. an array of zero-based day of week integers (0=Sunday)
        daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
        startTime: '09:00', // a start time (10am in this example)
        endTime: '17:00', // an end time (6pm in this example)
    },
    eventClick: function (info: any) {
        selectedEvent.value = info.event;
        isDetailsDialogOpen.value = true;
    },
    dateClick: function (info: any) {
        selectedDate.value = info.dateStr;
        isDialogOpen.value = true;
    },
    eventDrop: function (info: any) {
        // Store info to be used after confirmation
        pendingDropInfo.value = info;
        isRescheduleConfirmOpen.value = true;
    },
    height: 'auto',
}));

const handleRescheduleConfirm = (timePayload?: string) => {
    if (!pendingDropInfo.value) return;
    
    const info = pendingDropInfo.value;
    // Use the timePayload from the dialog if provided, otherwise fallback to drop info
    // However, eventDrop provides info.event.startStr which is what the dialog initialized the input with.
    // If user edited it, timePayload will be the new ISO string (local time usually from input type=datetime-local).
    
    let scheduledAt = timePayload;
    if (!scheduledAt) {
         scheduledAt = info.event.startStr;
    }

    router.post(`/dashboard/interviews/${info.event.id}/move`, {
        scheduled_at: scheduledAt
    }, {
        preserveScroll: true,
        onSuccess: () => {
             toast.success('Interview rescheduled successfully');
             pendingDropInfo.value = null;
             isRescheduleConfirmOpen.value = false;
        },
        onError: (errors) => {
            info.revert();
            const message = errors.scheduled_at || 'Failed to reschedule. Please try again.';
            toast.error(message);
            pendingDropInfo.value = null;
            isRescheduleConfirmOpen.value = false;
        }
    });
};

const handleRescheduleCancel = () => {
    if (pendingDropInfo.value) {
        pendingDropInfo.value.revert();
        pendingDropInfo.value = null;
    }
    isRescheduleConfirmOpen.value = false;
};

</script>

<template>
    <Head title="Interviews Calendar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Interviews Calendar
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manage and view upcoming scheduled interviews.
                    </p>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-6 flex flex-wrap items-center gap-4 rounded-lg bg-white p-4 shadow dark:bg-gray-800">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium">Job Position:</label>
                    <select v-model="filters.jobPositionId" class="h-9 rounded-md border text-sm dark:bg-gray-900 dark:border-gray-700">
                        <option value="">All Jobs</option>
                        <option v-for="job in job_positions" :key="job.id" :value="job.id">
                            {{ job.title }}
                        </option>
                    </select>
                </div>
                 <div class="flex items-center gap-2">
                    <label class="text-sm font-medium">Status:</label>
                    <select v-model="filters.status" class="h-9 rounded-md border text-sm dark:bg-gray-900 dark:border-gray-700">
                        <option value="">All Statuses</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                 <div class="ml-auto flex gap-2">
                    <Link href="/dashboard/interviews/pending">
                        <Button variant="secondary">Pending Links</Button>
                    </Link>
                    <GeneralBookingButton variant="outline" />
                    <Button @click="isDialogOpen = true">
                        Schedule Interview
                    </Button>
                </div>
            </div>

            <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-900">
                <FullCalendar :options="calendarOptions">
                    <template #eventContent="{ event }">
                         <div class="flex w-full items-center gap-1 overflow-hidden px-1 py-0.5 text-xs">
                            <div 
                                class="h-2 w-2 shrink-0 rounded-full"
                                :class="{
                                    'bg-green-500': event.extendedProps.status === 'scheduled',
                                    'bg-yellow-500': event.extendedProps.status === 'pending',
                                    'bg-red-500': event.extendedProps.status === 'cancelled',
                                    'bg-blue-500': event.extendedProps.status === 'completed',
                                }"
                            ></div>
                            <div class="truncate font-semibold text-gray-900 dark:text-white">
                                {{ event.extendedProps.applicant }}
                            </div>
                            <div class="hidden text-gray-500 sm:block">
                                - {{ event.title }}
                            </div>
                        </div>
                    </template>
                </FullCalendar>
            </div>
        </div>

        <CalendarSchedulingDialog
            v-model:is-open="isDialogOpen"
            :applications="applications"
            :initial-date="selectedDate"
        />

        <InterviewDetailsDialog
            v-model:is-open="isDetailsDialogOpen"
            :event="selectedEvent"
        />

        <DragRescheduleConfirmationDialog
             v-model:is-open="isRescheduleConfirmOpen"
             :event="pendingDropInfo?.event"
             @confirm="handleRescheduleConfirm"
             @cancel="handleRescheduleCancel"
        />
    </AppLayout>
</template>

<style>
/* Basic FullCalendar Overrides for better Dark Mode support if needed */
:root {
    --fc-border-color: #e5e7eb;
    --fc-daygrid-event-dot-width: 8px;
}
.dark {
    --fc-page-bg-color: #111827;
    --fc-neutral-bg-color: #1f2937;
    --fc-border-color: #374151;
    --fc-button-text-color: #fff;
    --fc-button-bg-color: #374151;
    --fc-button-border-color: #4b5563;
    --fc-button-hover-bg-color: #4b5563;
    --fc-button-hover-border-color: #6b7280;
    --fc-button-active-bg-color: #1f2937;
    --fc-button-active-border-color: #374151;
    --fc-event-bg-color: #3b82f6;
    --fc-event-border-color: #2563eb;
    --fc-today-bg-color: rgba(255, 255, 255, 0.05);
}
.dark .fc-theme-standard td,
.dark .fc-theme-standard th {
    border-color: var(--fc-border-color);
}
.dark .fc-col-header-cell-cushion,
.dark .fc-daygrid-day-number {
    color: #e5e7eb;
}
</style>
