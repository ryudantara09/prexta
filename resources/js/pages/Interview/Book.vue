<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Head, useForm } from '@inertiajs/vue3';
import { Calendar } from 'lucide-vue-next';
import { update } from '@/routes/interview';

const props = defineProps<{
    interview: {
        token: string;
        application?: {
            applicant: { full_name: string };
            job_position: { title: string; city: string; country: string };
        };
        meeting_title?: string;
    };
    slots: Record<
        string,
        Array<{ time: string; display: string; day: string }>
    >;
}>();

const form = useForm({
    scheduled_at: '',
    guest_name: '',
    guest_email: '',
});

const submit = () => {
    form.post(update.url(props.interview.token), {
        preserveScroll: true,
    });
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString(undefined, {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Schedule Interview" />

    <div
        class="flex min-h-screen flex-col items-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-gray-900"
    >
        <div class="w-full max-w-3xl space-y-8">
            <div class="text-center">
                <h2
                    class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white"
                >
                    {{
                        interview.application
                            ? 'Schedule Your Interview'
                            : 'Book a Meeting'
                    }}
                </h2>
                <div
                    v-if="interview.application"
                    class="mt-2 text-sm text-gray-600 dark:text-gray-400"
                >
                    <p>
                        Hi {{ interview.application.applicant.full_name }},
                        please select a time for your interview for the
                        {{ interview.application.job_position.title }} position.
                    </p>
                </div>
                <div
                    v-else
                    class="mt-2 text-sm text-gray-600 dark:text-gray-400"
                >
                    <p>
                        Please select a time for your:
                        <strong>{{
                            interview.meeting_title || 'Meeting'
                        }}</strong>
                    </p>
                </div>
            </div>

            <Card v-if="!interview.application" class="mb-8">
                <CardHeader>
                    <CardTitle>Your Information</CardTitle>
                    <CardDescription
                        >Tell us who you are so we can confirm the
                        meeting.</CardDescription
                    >
                </CardHeader>
                <CardContent class="grid gap-4">
                    <div class="grid gap-2">
                        <label for="name" class="text-sm font-medium"
                            >Full Name</label
                        >
                        <input
                            id="name"
                            type="text"
                            v-model="form.guest_name"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:bg-gray-800"
                            placeholder="John Doe"
                        />
                        <p
                            v-if="form.errors.guest_name"
                            class="text-xs text-red-500"
                        >
                            {{ form.errors.guest_name }}
                        </p>
                    </div>
                    <div class="grid gap-2">
                        <label for="email" class="text-sm font-medium"
                            >Email Address</label
                        >
                        <input
                            id="email"
                            type="email"
                            v-model="form.guest_email"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:bg-gray-800"
                            placeholder="john@example.com"
                        />
                        <p
                            v-if="form.errors.guest_email"
                            class="text-xs text-red-500"
                        >
                            {{ form.errors.guest_email }}
                        </p>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Available Time Slots</CardTitle>
                    <CardDescription
                        >All times are in your local time zone (server time used
                        for now, logic handles it).</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <div
                        v-if="Object.keys(slots).length === 0"
                        class="py-12 text-center"
                    >
                        <p class="text-gray-500">
                            No time slots available right now. Please contact
                            the recruiter.
                        </p>
                    </div>

                    <div v-else class="space-y-6">
                        <div v-for="(daySlots, date) in slots" :key="date">
                            <h3
                                class="mb-3 flex items-center text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                <Calendar class="mr-2 h-4 w-4" />
                                {{ formatDate(date) }}
                            </h3>
                            <div
                                class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4"
                            >
                                <button
                                    v-for="slot in daySlots"
                                    :key="slot.time"
                                    type="button"
                                    @click="form.scheduled_at = slot.time"
                                    :class="[
                                        'flex items-center justify-center rounded-md border px-4 py-2 text-sm font-medium transition-colors focus:ring-2 focus:ring-offset-2 focus:outline-none',
                                        form.scheduled_at === slot.time
                                            ? 'border-primary bg-primary text-primary-foreground ring-2 ring-primary ring-offset-2'
                                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700',
                                    ]"
                                    :disabled="form.processing"
                                >
                                    {{ slot.display }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-6" v-if="form.scheduled_at">
                        <p
                            class="mb-4 text-center text-gray-600 dark:text-gray-300"
                        >
                            You have selected:
                            <span class="font-bold">{{
                                new Date(form.scheduled_at).toLocaleString()
                            }}</span>
                        </p>
                        <Button
                            class="block w-full sm:mx-auto sm:w-auto"
                            size="lg"
                            :disabled="form.processing"
                            @click="submit"
                        >
                            {{
                                form.processing
                                    ? 'Confirming...'
                                    : 'Confirm Interview'
                            }}
                        </Button>
                        <p
                            v-if="form.errors.scheduled_at"
                            class="mt-2 text-center text-sm text-red-600"
                        >
                            {{ form.errors.scheduled_at }}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
