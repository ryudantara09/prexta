<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/ApplicationController';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    job: {
        id: number;
        title: string;
        description: string;
        city: string;
        country: string;
        created_at: string;
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
    <Head :title="`${job.title} - Job Opening`" />

    <div
        class="min-h-screen bg-[#FDFDFC] px-4 py-12 text-[#1b1b18] sm:px-6 lg:px-8 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
    >
        <div class="mx-auto max-w-3xl">
            <!-- Back link & Toggle -->
            <div class="mb-4 flex items-center justify-between">
                <Link
                    href="/jobs"
                    class="inline-block text-sm text-[#706f6c] hover:text-[#1b1b18] dark:text-[#A1A09A] dark:hover:text-[#EDEDEC]"
                >
                    ← Back to All Jobs
                </Link>
                <AppearanceTabs />
            </div>

            <!-- Job Details Card -->
            <div
                class="mt-6 overflow-hidden rounded-lg border border-[#19140035] bg-white shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]"
            >
                <div class="p-8">
                    <div class="mb-6">
                        <h1
                            class="mb-2 text-3xl font-bold tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]"
                        >
                            {{ job.title }}
                        </h1>
                        <p class="text-lg text-[#706f6c] dark:text-[#A1A09A]">
                            📍 {{ job.city }}, {{ job.country }}
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2
                            class="mb-3 text-xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC]"
                        >
                            Job Description
                        </h2>
                        <p
                            class="leading-relaxed whitespace-pre-wrap text-[#706f6c] dark:text-[#A1A09A]"
                        >
                            {{ job.description }}
                        </p>
                    </div>
                </div>

                <!-- Application Form Section -->
                <div
                    class="border-t border-[#19140035] bg-[#FDFDFC]/50 p-8 dark:border-[#3E3E3A] dark:bg-[#0a0a0a]/50"
                >
                    <h2
                        class="mb-6 text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]"
                    >
                        Apply for this Position
                    </h2>

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
                            <p
                                class="text-sm text-[#706f6c] dark:text-[#A1A09A]"
                            >
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
