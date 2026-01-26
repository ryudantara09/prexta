<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, destroy, edit, show } from '@/routes/dashboard/job-positions';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps<{
    jobPositions: Array<{
        id: number;
        title: string;
        description: string;
        city: string;
        country: string;
        created_at: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Job Positions',
        href: '/dashboard/job-positions',
    },
];

const deleteJob = (id: number) => {
    if (confirm('Are you sure you want to delete this job position?')) {
        router.delete(destroy.url(id));
    }
};
</script>

<template>
    <Head title="Job Positions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <h1
                    class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                >
                    Job Positions
                </h1>
                <Link :href="create.url()">
                    <Button>Create New Job</Button>
                </Link>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-zinc-900"
            >
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-800"
                >
                    <thead class="bg-gray-50 dark:bg-zinc-800/50">
                        <tr>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Title
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Location
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Created At
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 bg-white dark:divide-gray-800 dark:bg-zinc-900"
                    >
                        <tr v-for="job in jobPositions" :key="job.id">
                            <td
                                class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                            >
                                {{ job.title }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400"
                            >
                                {{ job.city }}, {{ job.country }}
                            </td>
                            <td
                                class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400"
                            >
                                {{
                                    new Date(
                                        job.created_at,
                                    ).toLocaleDateString()
                                }}
                            </td>
                            <td
                                class="space-x-2 px-6 py-4 text-right text-sm font-medium whitespace-nowrap"
                            >
                                <Link
                                    :href="show.url(job.id)"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                    >View</Link
                                >
                                <Link
                                    :href="edit.url(job.id)"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                    >Edit</Link
                                >
                                <button
                                    @click="deleteJob(job.id)"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="jobPositions.length === 0">
                            <td
                                colspan="4"
                                class="px-6 py-4 text-center text-gray-500 dark:text-gray-400"
                            >
                                No job positions found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
