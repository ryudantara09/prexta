<script setup lang="ts">
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { computed, ref } from 'vue';

type Sector = {
    title: string;
    description: string;
    icon: string;
    color: 'prexta-blue' | 'prexta-cyan' | 'prexta-indigo';
};

const props = defineProps<{
    sector: Sector;
    index: number;
}>();

const isOpen = ref(false);

const colorClasses = computed(() => {
    if (props.sector.color === 'prexta-cyan') {
        return {
            iconBg: 'bg-prexta-cyan/10',
            iconText: 'text-prexta-cyan',
            ring: 'group-hover:ring-prexta-cyan/5',
            marker: 'bg-prexta-cyan',
        };
    }

    if (props.sector.color === 'prexta-indigo') {
        return {
            iconBg: 'bg-prexta-indigo/10',
            iconText: 'text-prexta-indigo',
            ring: 'group-hover:ring-prexta-indigo/5',
            marker: 'bg-prexta-indigo',
        };
    }

    return {
        iconBg: 'bg-prexta-blue/10',
        iconText: 'text-prexta-blue',
        ring: 'group-hover:ring-prexta-blue/5',
        marker: 'bg-prexta-blue',
    };
});

const parsedDescription = computed(() => {
    const lines = props.sector.description
        .split('\n')
        .map((line) => line.trim())
        .filter(Boolean);

    const firstListIndex = lines.findIndex((line) => /^[-●]\s+/.test(line));

    if (firstListIndex === -1) {
        return {
            intro: lines.join(' '),
            points: [] as string[],
        };
    }

    return {
        intro: lines.slice(0, firstListIndex).join(' '),
        points: lines
            .slice(firstListIndex)
            .map((line) => line.replace(/^[-●]\s+/, '')),
    };
});
</script>

<template>
    <button
        type="button"
        class="group reveal reveal-fade-up relative w-full rounded-3xl border border-gray-100 bg-white p-6 text-left transition-all duration-300 hover:shadow-[0_16px_32px_-20px_rgba(0,0,0,0.24)] focus-visible:ring-2 focus-visible:ring-prexta-blue/40 focus-visible:outline-none dark:border-gray-800 dark:bg-gray-900/40"
        :style="{ '--delay': `${index * 120}ms` }"
        @click="isOpen = true"
    >
        <div
            class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl ring-8 ring-transparent transition-colors duration-300 group-hover:bg-prexta-gradient group-hover:text-white"
            :class="[
                colorClasses.iconBg,
                colorClasses.iconText,
                colorClasses.ring,
            ]"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="sector.icon"
                />
            </svg>
        </div>

        <h3
            class="group-hover:text-gradient mb-3 text-xl leading-tight font-black transition-all"
        >
            {{ sector.title }}
        </h3>

        <p
            class="line-clamp-2 text-sm leading-relaxed text-gray-500 dark:text-gray-400"
        >
            {{ parsedDescription.intro }}
        </p>

        <div
            class="mt-4 inline-flex items-center gap-2 text-[11px] font-black tracking-[0.14em] text-prexta-blue uppercase"
        >
            Ouvrir le detail
            <span class="h-px w-6 bg-prexta-blue/60"></span>
        </div>
    </button>

    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="max-w-2xl rounded-3xl p-0">
            <div
                class="rounded-3xl border border-gray-100 bg-white p-7 dark:border-gray-800 dark:bg-gray-900"
            >
                <DialogHeader class="space-y-4">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl"
                        :class="[colorClasses.iconBg, colorClasses.iconText]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="sector.icon"
                            />
                        </svg>
                    </div>
                    <DialogTitle class="text-2xl leading-tight font-black">
                        {{ sector.title }}
                    </DialogTitle>
                    <DialogDescription
                        class="text-sm leading-relaxed text-gray-600 dark:text-gray-300"
                    >
                        {{ parsedDescription.intro }}
                    </DialogDescription>
                </DialogHeader>

                <ul class="mt-6 grid gap-3">
                    <li
                        v-for="point in parsedDescription.points"
                        :key="point"
                        class="grid grid-cols-[14px_1fr] items-start gap-3 rounded-xl border border-gray-100 bg-gray-50/70 px-4 py-3 text-sm leading-relaxed text-gray-700 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-300"
                    >
                        <span
                            class="mt-1.5 h-1.5 w-1.5 rounded-full"
                            :class="colorClasses.marker"
                        ></span>
                        <span>{{ point }}</span>
                    </li>
                </ul>
            </div>
        </DialogContent>
    </Dialog>
</template>
