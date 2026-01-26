<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { store } from '@/routes/dashboard/job-positions';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Job Positions',
        href: '/dashboard/job-positions',
    },
    {
        title: 'Create',
        href: '/dashboard/job-positions/create',
    },
];

const form = useForm({
    title: '',
    description: '',
    city: '',
    country: '',
});

const submit = () => {
    form.post(store.url());
};
</script>

<template>
    <Head title="Create Job Position" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl p-6">
            <h1
                class="mb-6 text-2xl font-semibold text-gray-900 dark:text-gray-100"
            >
                Create Job Position
            </h1>

            <form
                @submit.prevent="submit"
                class="space-y-6 rounded-lg border border-gray-200 bg-white p-6 shadow dark:border-gray-800 dark:bg-zinc-900"
            >
                <div class="space-y-2">
                    <Label for="title">Job Title</Label>
                    <Input
                        id="title"
                        v-model="form.title"
                        type="text"
                        placeholder="e.g. Software Engineer"
                        required
                    />
                    <div v-if="form.errors.title" class="text-sm text-red-500">
                        {{ form.errors.title }}
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="description">Description</Label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Job details..."
                        required
                    ></textarea>
                    <div
                        v-if="form.errors.description"
                        class="text-sm text-red-500"
                    >
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <Label for="city">City</Label>
                        <Input
                            id="city"
                            v-model="form.city"
                            type="text"
                            placeholder="e.g. Tunis"
                            required
                        />
                        <div
                            v-if="form.errors.city"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.city }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <Label for="country">Country</Label>
                        <Input
                            id="country"
                            v-model="form.country"
                            type="text"
                            placeholder="e.g. Tunisia"
                            required
                        />
                        <div
                            v-if="form.errors.country"
                            class="text-sm text-red-500"
                        >
                            {{ form.errors.country }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <Button type="submit" :disabled="form.processing">
                        Create Position
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
