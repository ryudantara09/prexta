<script setup lang="ts">
import {
    index as jobsIndex,
    show as jobsShow,
} from '@/actions/App/Http/Controllers/PublicJobController';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Head, Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';

const props = defineProps<{
    jobs: Array<{
        id: number;
        title: string;
        description: string;
        city: string;
        country: string;
        created_at: string;
    }>;
    filters: {
        search?: string;
    };
}>();

const search = ref(props.filters.search || '');

watch(
    search,
    debounce((value: string) => {
        router.get(
            jobsIndex.url({ query: { search: value } }),
            {},
            { preserveState: true, replace: true },
        );
    }, 300),
);

// Truncate description for the card view
const truncate = (text: string, length: number) => {
    return text.length > length ? text.substring(0, length) + '...' : text;
};
</script>

<template>
    <Head title="Browse Jobs | Prexta" />

    <div
        class="relative min-h-screen overflow-hidden bg-white text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
    >
        <!-- Background Glow Effects -->
        <div
            class="pointer-events-none absolute -top-24 -left-24 h-96 w-96 rounded-full bg-prexta-blue/10 blur-[120px] dark:bg-prexta-blue/5"
        ></div>
        <div
            class="pointer-events-none absolute top-1/2 -right-24 h-96 w-96 -translate-y-1/2 rounded-full bg-prexta-cyan/10 blur-[120px] dark:bg-prexta-cyan/5"
        ></div>

        <div class="relative z-10 mx-auto max-w-6xl px-6 py-16 lg:py-24">
            <!-- Navigation -->
            <nav class="mb-16 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2">
                    <div class="h-6 w-6 rounded bg-prexta-gradient"></div>
                    <span class="text-lg font-bold tracking-tight">Prexta</span>
                </Link>
                <AppearanceTabs />
            </nav>

            <!-- Hero/Header Section -->
            <div class="mb-16">
                <h1
                    class="mb-6 text-5xl leading-none font-black tracking-tighter md:text-7xl"
                >
                    Join our <span class="text-gradient">mission.</span>
                </h1>
                <p
                    class="max-w-2xl text-xl leading-relaxed text-gray-600 dark:text-gray-400"
                >
                    We're looking for passionate individuals to help us redefine
                    the future of work. Discover your next challenge below.
                </p>
            </div>

            <!-- Search Bar -->
            <div class="mb-12">
                <div class="group relative max-w-xl">
                    <div
                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4"
                    >
                        <svg
                            class="h-5 w-5 text-gray-400 transition-colors group-focus-within:text-prexta-blue"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </div>
                    <Input
                        v-model="search"
                        placeholder="Search jobs, locations, or keywords..."
                        class="h-14 w-full rounded-2xl border-gray-100 bg-white pl-12 shadow-sm transition-all focus:ring-2 focus:ring-prexta-blue/20 dark:border-gray-800 dark:bg-gray-950"
                    />
                </div>
            </div>

            <!-- Job Listings -->
            <div class="space-y-4">
                <template v-if="jobs.length > 0">
                    <div
                        v-for="job in jobs"
                        :key="job.id"
                        class="group relative overflow-hidden rounded-[2rem] border border-gray-100 bg-white p-8 transition-all hover:-translate-y-1 hover:shadow-2xl hover:shadow-prexta-blue/5 dark:border-gray-800 dark:bg-gray-900/40"
                    >
                        <!-- Hover Gradient Accent -->
                        <div
                            class="pointer-events-none absolute inset-0 bg-prexta-gradient opacity-0 transition-opacity group-hover:opacity-[0.02]"
                        ></div>

                        <div
                            class="relative z-10 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center"
                        >
                            <div class="flex-1">
                                <div
                                    class="mb-3 flex flex-wrap items-center gap-3"
                                >
                                    <span
                                        class="rounded-full bg-gray-100 px-3 py-1 text-[10px] font-bold tracking-wider text-gray-500 uppercase dark:bg-gray-800 dark:text-gray-400"
                                        >Full Time</span
                                    >
                                    <span
                                        class="rounded-full bg-prexta-blue/10 px-3 py-1 text-[10px] font-bold tracking-wider text-prexta-blue uppercase"
                                        >📍 {{ job.city }}</span
                                    >
                                </div>

                                <h2
                                    class="group-hover:text-gradient text-2xl font-black transition-all"
                                >
                                    <Link :href="jobsShow.url(job.id)">
                                        {{ job.title }}
                                    </Link>
                                </h2>

                                <p
                                    class="mt-4 line-clamp-2 max-w-3xl leading-relaxed text-gray-600 dark:text-gray-400"
                                >
                                    {{ truncate(job.description, 180) }}
                                </p>
                            </div>

                            <div class="flex w-full items-center md:w-auto">
                                <Link
                                    :href="jobsShow.url(job.id)"
                                    class="w-full"
                                >
                                    <Button
                                        variant="outline"
                                        class="h-12 w-full rounded-full border-2 border-gray-100 px-8 font-bold transition-all hover:bg-black hover:text-white md:w-auto dark:border-gray-800 dark:hover:bg-white dark:hover:text-black"
                                    >
                                        View Details
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>

                <div
                    v-else
                    class="rounded-[3rem] border-2 border-dashed border-gray-100 bg-gray-50/50 py-24 text-center dark:border-gray-800 dark:bg-gray-900/20"
                >
                    <div
                        class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800"
                    >
                        <svg
                            class="h-8 w-8 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <p
                        class="mb-2 text-xl font-bold text-gray-900 dark:text-white"
                    >
                        No positions found.
                    </p>
                    <p class="mb-8 text-gray-600 dark:text-gray-400">
                        Try adjusting your search terms or filters.
                    </p>
                    <Button
                        v-if="search"
                        variant="link"
                        @click="search = ''"
                        class="text-lg font-bold text-prexta-blue"
                    >
                        Clear your search
                    </Button>
                </div>
            </div>

            <!-- Footer-like back button -->
            <div class="mt-16 text-center">
                <Link
                    href="/"
                    class="group inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition-colors hover:text-black dark:hover:text-white"
                >
                    <span
                        class="transition-transform group-hover:-translate-x-1"
                        >←</span
                    >
                    Back to homepage
                </Link>
            </div>
        </div>
    </div>
</template>

<style>
.text-gradient {
    background-image: linear-gradient(to right, #0066ff, #00d1ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.bg-prexta-gradient {
    background-image: linear-gradient(to right, #0066ff, #00d1ff);
}
</style>
