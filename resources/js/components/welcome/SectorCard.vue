<script setup lang="ts">
import { computed } from 'vue';

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

const colorClasses = computed(() => {
    if (props.sector.color === 'prexta-cyan') {
        return {
            iconBg: 'bg-prexta-cyan/10',
            iconText: 'text-prexta-cyan',
            ring: 'group-hover:ring-prexta-cyan/5',
        };
    }

    if (props.sector.color === 'prexta-indigo') {
        return {
            iconBg: 'bg-prexta-indigo/10',
            iconText: 'text-prexta-indigo',
            ring: 'group-hover:ring-prexta-indigo/5',
        };
    }

    return {
        iconBg: 'bg-prexta-blue/10',
        iconText: 'text-prexta-blue',
        ring: 'group-hover:ring-prexta-blue/5',
    };
});
</script>

<template>
    <div
        class="group reveal reveal-fade-up relative rounded-3xl border border-gray-100 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_24px_48px_-16px_rgba(0,0,0,0.14)] dark:border-gray-800 dark:bg-gray-900/40"
        :style="{ '--delay': `${index * 120}ms` }"
    >
        <div
            class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl ring-8 ring-transparent transition-all group-hover:rotate-3 group-hover:bg-prexta-gradient group-hover:text-white"
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
            class="text-sm leading-relaxed whitespace-pre-line text-gray-500 dark:text-gray-400"
        >
            {{ sector.description }}
        </p>
    </div>
</template>
