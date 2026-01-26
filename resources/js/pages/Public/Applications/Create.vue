<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { store } from '@/routes/applications';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    job: {
        id: number;
        title: string;
        city: string;
        country: string;
    };
}>();

const form = useForm({
    full_name: '',
    email: '',
    cv: null as File | null,
    cover_letter: '',
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.cv = target.files[0];
    }
};

const submit = () => {
    form.post(store.url(props.job.id));
};
</script>

<template>
    <Head :title="`Apply for ${job.title}`" />

    <div
        class="min-h-screen bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-zinc-950"
    >
        <div class="mx-auto max-w-2xl">
            <!-- Back link -->
            <Link
                :href="`/jobs/${job.id}`"
                class="mb-4 inline-block text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
            >
                ← Back to Job
            </Link>

            <!-- Application Form -->
            <div
                class="mt-6 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-zinc-900"
            >
                <div class="p-8">
                    <h1
                        class="mb-2 text-2xl font-bold text-gray-900 dark:text-gray-100"
                    >
                        Apply for {{ job.title }}
                    </h1>
                    <p class="mb-8 text-gray-600 dark:text-gray-400">
                        {{ job.city }}, {{ job.country }}
                    </p>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <Label for="full_name"
                                >Full Name
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="full_name"
                                v-model="form.full_name"
                                type="text"
                                placeholder="John Doe"
                                required
                            />
                            <div
                                v-if="form.errors.full_name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.full_name }}
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <Label for="email"
                                >Email
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="john@example.com"
                                required
                            />
                            <div
                                v-if="form.errors.email"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>

                        <!-- CV Upload -->
                        <div class="space-y-2">
                            <Label for="cv"
                                >CV / Resume
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="cv"
                                type="file"
                                accept=".pdf,.doc,.docx"
                                @change="handleFileChange"
                                required
                                class="cursor-pointer"
                            />
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Accepted formats: PDF, DOC, DOCX (max 5MB)
                            </p>
                            <div
                                v-if="form.errors.cv"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.cv }}
                            </div>
                        </div>

                        <!-- Cover Letter -->
                        <div class="space-y-2">
                            <Label for="cover_letter"
                                >Cover Letter / Motivation (Optional)</Label
                            >
                            <textarea
                                id="cover_letter"
                                v-model="form.cover_letter"
                                rows="6"
                                class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                placeholder="Tell us why you're interested in this position..."
                            ></textarea>
                            <div
                                v-if="form.errors.cover_letter"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.cover_letter }}
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                size="lg"
                            >
                                <span v-if="form.processing"
                                    >Submitting...</span
                                >
                                <span v-else>Submit Application</span>
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
