<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Head } from '@inertiajs/vue3';
import { Calendar, CheckCircle, MapPin } from 'lucide-vue-next';

const props = defineProps<{
    interview: {
        scheduled_at: string;
        application?: {
            applicant: { full_name: string };
            job_position: { title: string; city: string; country: string };
        };
        meeting_title?: string;
        guest_name?: string;
    };
}>();

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString(undefined, {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Interview Confirmed" />

    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-gray-900"
    >
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900"
                >
                    <CheckCircle
                        class="h-8 w-8 text-green-600 dark:text-green-300"
                    />
                </div>
                <h2
                    class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white"
                >
                    Confirmed!
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Your interview has been successfully scheduled.
                </p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle class="text-center">Interview Details</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                    <div class="flex items-start">
                        <Calendar class="mt-0.5 mr-3 h-5 w-5 text-gray-400" />
                        <div>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Date & Time
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ formatDateTime(interview.scheduled_at) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <MapPin class="mt-0.5 mr-3 h-5 w-5 text-gray-400" />
                        <div v-if="interview.application">
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Position
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ interview.application.job_position.title }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ interview.application.job_position.city }},
                                {{ interview.application.job_position.country }}
                            </p>
                        </div>
                        <div v-else>
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                Meeting
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{
                                    interview.meeting_title || 'General Meeting'
                                }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <div class="text-center">
                <p class="text-sm text-gray-500">
                    A confirmation email has been sent to your email address.
                </p>
            </div>
        </div>
    </div>
</template>
