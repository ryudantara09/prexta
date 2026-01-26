<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { updateNote } from '@/routes/dashboard/applications';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

interface Application {
    id: number;
    applicant: {
        id: number;
        full_name: string;
        email: string;
    };
    job_position: {
        id: number;
        title: string;
    };
    cv_path: string;
    cover_letter: string | null;
    recruiter_note: string | null;
    created_at: string;
    interview: {
        id: number;
        token: string;
        status: 'pending' | 'scheduled' | 'cancelled' | 'completed';
        scheduled_at: string | null;
    } | null;
}

const props = defineProps<{
    applications: Application[];
    jobPositions: Array<{ id: number; title: string }>;
    applicants: Array<{ id: number; full_name: string }>;
    filters: {
        job_position_id?: number;
        applicant_id?: number;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Applications',
        href: '/dashboard/applications',
    },
];

const selectedJobPosition = ref<string | undefined>(
    props.filters.job_position_id?.toString(),
);
const selectedApplicant = ref<string | undefined>(
    props.filters.applicant_id?.toString(),
);

const editingNoteId = ref<number | null>(null);
const noteForm = ref<{ [key: number]: string }>({});

// Initialize note forms
props.applications.forEach((app) => {
    noteForm.value[app.id] = app.recruiter_note || '';
});

// Watch filters and reload page when they change
watch([selectedJobPosition, selectedApplicant], () => {
    router.get(
        '/dashboard/applications',
        {
            job_position_id: selectedJobPosition.value,
            applicant_id: selectedApplicant.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
});

const startEditingNote = (applicationId: number) => {
    editingNoteId.value = applicationId;
};

const saveNote = (applicationId: number) => {
    router.patch(
        updateNote.url(applicationId),
        {
            recruiter_note: noteForm.value[applicationId],
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                editingNoteId.value = null;
            },
        },
    );
};

const cancelEditingNote = () => {
    editingNoteId.value = null;
};

const downloadCV = (cvPath: string) => {
    window.open(`/storage/${cvPath}`, '_blank');
};

const generateInterviewLink = (applicationId: number) => {
    // using manual route string since wayfinder might not have generated it yet or I want to be safe
    router.post(
        `/dashboard/applications/${applicationId}/interview`,
        {},
        {
            preserveScroll: true,
            onSuccess: (page) => {
                const flash = page.props.flash as any;
                if (flash && flash.data && flash.data.interview_url) {
                    navigator.clipboard.writeText(flash.data.interview_url);
                    alert(
                        `Interview Link Generated and Copied to Clipboard:\n\n${flash.data.interview_url}`,
                    );
                }
            },
        },
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Applications" />

        <div class="p-6">
            <div class="mb-6">
                <h1
                    class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                >
                    Applications
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    View and manage all job applications
                </p>
            </div>

            <!-- Filters -->
            <div class="mb-6 flex gap-4">
                <!-- Job Position Filter -->
                <div class="w-64">
                    <select
                        v-model="selectedJobPosition"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                    >
                        <option value="">All Job Positions</option>
                        <option
                            v-for="job in jobPositions"
                            :key="job.id"
                            :value="job.id.toString()"
                        >
                            {{ job.title }}
                        </option>
                    </select>
                </div>

                <!-- Applicant Filter -->
                <div class="w-64">
                    <select
                        v-model="selectedApplicant"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                    >
                        <option value="">All Applicants</option>
                        <option
                            v-for="applicant in applicants"
                            :key="applicant.id"
                            :value="applicant.id.toString()"
                        >
                            {{ applicant.full_name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Applications Table -->
            <div
                class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow dark:border-gray-800 dark:bg-gray-900"
            >
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-800"
                >
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Applicant
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Job Position
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Applied On
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                CV
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Recruiter Note
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-gray-900"
                    >
                        <tr
                            v-for="application in applications"
                            :key="application.id"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ application.applicant.full_name }}
                                </div>
                                <div
                                    class="text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ application.applicant.email }}
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ application.job_position.title }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400"
                            >
                                {{
                                    new Date(
                                        application.created_at,
                                    ).toLocaleDateString()
                                }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    @click="downloadCV(application.cv_path)"
                                >
                                    Download CV
                                </Button>
                            </td>
                            <td class="px-6 py-4">
                                <div
                                    v-if="editingNoteId === application.id"
                                    class="flex gap-2"
                                >
                                    <textarea
                                        v-model="noteForm[application.id]"
                                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                                        rows="3"
                                        placeholder="Add a note..."
                                    ></textarea>
                                    <div class="flex flex-col gap-2">
                                        <Button
                                            size="sm"
                                            @click="saveNote(application.id)"
                                        >
                                            Save
                                        </Button>
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            @click="cancelEditingNote"
                                        >
                                            Cancel
                                        </Button>
                                    </div>
                                </div>
                                <div v-else class="flex flex-col gap-2">
                                    <div class="flex items-start gap-2">
                                        <p
                                            class="flex-1 text-sm text-gray-900 dark:text-gray-100"
                                            :class="{
                                                'text-gray-400':
                                                    !application.recruiter_note,
                                            }"
                                        >
                                            {{
                                                application.recruiter_note ||
                                                'No note yet...'
                                            }}
                                        </p>
                                        <Button
                                            size="sm"
                                            variant="ghost"
                                            @click="
                                                startEditingNote(application.id)
                                            "
                                        >
                                            Edit
                                        </Button>
                                    </div>
                                    <div class="flex justify-end">
                                        <div v-if="application.interview" class="flex flex-col items-end gap-1">
                                            <span class="text-xs font-medium uppercase" :class="{
                                                'text-yellow-600': application.interview.status === 'pending',
                                                'text-green-600': application.interview.status === 'scheduled',
                                                'text-red-600': application.interview.status === 'cancelled',
                                                'text-blue-600': application.interview.status === 'completed'
                                            }">
                                                {{ application.interview.status }}
                                            </span>
                                            <span v-if="application.interview.scheduled_at" class="text-xs text-gray-500">
                                                {{ new Date(application.interview.scheduled_at).toLocaleDateString() }} {{ new Date(application.interview.scheduled_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                            </span>
                                            <Button 
                                                size="sm" 
                                                variant="outline"
                                                class="h-7 px-2 text-xs"
                                                @click="copyInterviewLink(application.interview.token)"
                                            >
                                                Copy Link
                                            </Button>
                                        </div>
                                        <Button
                                            v-else
                                            size="sm" 
                                            variant="secondary"
                                            class="h-7 px-2 text-xs"
                                            @click="
                                                generateInterviewLink(
                                                    application.id,
                                                )
                                            "
                                        >
                                            Schedule Interview
                                        </Button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="applications.length === 0">
                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                No applications found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
