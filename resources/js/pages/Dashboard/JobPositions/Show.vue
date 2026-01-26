<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { destroy, edit } from '@/routes/dashboard/job-positions';
import { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    jobPosition: {
        id: number;
        title: string;
        description: string;
        city: string;
        country: string;
        created_at: string;
        updated_at: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Job Positions',
        href: '/dashboard/job-positions',
    },
    {
        title: props.jobPosition.title,
        href: `/dashboard/job-positions/${props.jobPosition.id}`,
    },
];

const deleteJob = () => {
    if (confirm('Are you sure you want to delete this job position?')) {
        router.delete(destroy.url(props.jobPosition.id));
    }
};
</script>

<template>
    <Head :title="jobPosition.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-4xl p-6">
            <div class="mb-6 flex items-start justify-between">
                <div>
                    <h1
                        class="text-3xl font-bold text-gray-900 dark:text-gray-100"
                    >
                        {{ jobPosition.title }}
                    </h1>
                    <div
                        class="mt-2 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400"
                    >
                        <span
                            >{{ jobPosition.city }},
                            {{ jobPosition.country }}</span
                        >
                        <span>&bull;</span>
                        <span
                            >Posted on
                            {{
                                new Date(
                                    jobPosition.created_at,
                                ).toLocaleDateString()
                            }}</span
                        >
                    </div>
                </div>
                <div class="flex gap-2">
                    <Link :href="edit.url(jobPosition.id)">
                        <Button variant="outline">Edit</Button>
                    </Link>
                    <Button variant="destructive" @click="deleteJob"
                        >Delete</Button
                    >
                </div>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-zinc-900"
            >
                <div class="p-6">
                    <h3
                        class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100"
                    >
                        Job Description
                    </h3>
                    <div
                        class="prose dark:prose-invert max-w-none whitespace-pre-wrap text-gray-700 dark:text-gray-300"
                    >
                        {{ jobPosition.description }}
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
