<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Application Submitted" />

    <div
        class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8 dark:bg-zinc-950"
    >
        <div class="w-full max-w-md">
            <div
                class="overflow-hidden rounded-lg border border-gray-200 bg-white p-8 text-center shadow-lg dark:border-gray-800 dark:bg-zinc-900"
            >
                <!-- Success Icon -->
                <div
                    class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/20"
                >
                    <svg
                        class="h-10 w-10 text-green-600 dark:text-green-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>

                <h1
                    class="mb-3 text-2xl font-bold text-gray-900 dark:text-gray-100"
                >
                    Application Submitted!
                </h1>
                <p class="mb-6 text-gray-600 dark:text-gray-400">
                    Thank you for applying. We have received your application
                    and will review it shortly. We'll contact you via email if
                    we'd like to move forward.
                </p>

                <Link href="/">
                    <Button variant="outline" class="w-full">
                        Back to Home
                    </Button>
                </Link>
            </div>
        </div>
    </div>
</template>
