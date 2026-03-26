<script setup lang="ts">
import { computed, ref } from 'vue';

type InsightItem = {
    title?: string;
    body: string;
};

const props = withDefaults(
    defineProps<{
        heading?: string;
        footer: string;
        items: InsightItem[];
        accent?: 'blue' | 'cyan';
    }>(),
    {
        heading: '',
        accent: 'blue',
    },
);

const activeIndex = ref(-1);

const accentClasses = computed(() => {
    if (props.accent === 'cyan') {
        return {
            chip: 'text-prexta-cyan',
            active: 'border-prexta-cyan/35 bg-prexta-cyan/8 dark:bg-prexta-cyan/12',
            ring: 'focus-visible:ring-prexta-cyan',
            bar: 'bg-prexta-cyan/50',
            bullet: 'border-prexta-cyan/60 bg-prexta-cyan/15 text-prexta-cyan',
            glow: 'from-prexta-cyan/20 via-transparent to-transparent',
        };
    }

    return {
        chip: 'text-prexta-blue',
        active: 'border-prexta-blue/35 bg-prexta-blue/8 dark:bg-prexta-blue/12',
        ring: 'focus-visible:ring-prexta-blue',
        bar: 'bg-prexta-blue/50',
        bullet: 'border-prexta-blue/60 bg-prexta-blue/15 text-prexta-blue',
        glow: 'from-prexta-blue/20 via-transparent to-transparent',
    };
});
</script>

<template>
    <section
        class="group relative overflow-hidden rounded-[1.75rem] border border-gray-200/70 bg-white/90 p-4 shadow-[0_14px_30px_-20px_rgba(0,0,0,0.45)] transition-all duration-300 sm:p-5 dark:border-gray-700/80 dark:bg-gray-900/80"
        @mouseleave="activeIndex = -1"
    >
        <div
            class="pointer-events-none absolute inset-0 bg-gradient-to-br opacity-90"
            :class="accentClasses.glow"
        ></div>
        <div
            class="pointer-events-none absolute inset-x-4 top-0 h-px bg-white/70 dark:bg-white/20"
        ></div>

        <div class="relative z-10 space-y-3">
            <div v-if="heading" class="flex items-center gap-3">
                <h3
                    class="text-[11px] font-black tracking-[0.2em] text-gray-700 uppercase dark:text-gray-100"
                >
                    {{ heading }}
                </h3>
                <div
                    class="h-px flex-1 bg-gray-300/80 dark:bg-gray-600/70"
                ></div>
            </div>

            <div class="grid gap-1.5">
                <button
                    v-for="(item, index) in items"
                    :key="`${item.title ?? 'item'}-${index}`"
                    type="button"
                    class="grid cursor-pointer grid-cols-[1.5rem_1fr] items-start gap-2 rounded-xl border border-gray-200/70 bg-white/70 px-3 py-2 text-left text-sm backdrop-blur-sm transition-all duration-300 focus-visible:outline-none sm:px-3.5 sm:py-2.5 dark:border-gray-700/80 dark:bg-gray-800/70"
                    :class="[
                        activeIndex === index
                            ? `${accentClasses.active} scale-[1.01] shadow-[0_10px_24px_-18px_rgba(0,0,0,0.8)]`
                            : 'opacity-82 hover:opacity-100',
                        accentClasses.ring,
                    ]"
                    @mouseenter="activeIndex = index"
                    @focus="activeIndex = index"
                    @blur="activeIndex = -1"
                >
                    <span
                        class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full border text-[10px] font-black"
                        :class="accentClasses.bullet"
                    >
                        {{ index + 1 }}
                    </span>

                    <p
                        class="overflow-hidden leading-relaxed text-gray-700 transition-[max-height,opacity] duration-300 dark:text-gray-200"
                        :class="
                            activeIndex === index
                                ? 'max-h-40 opacity-100'
                                : 'max-h-5 opacity-85'
                        "
                    >
                        <span
                            v-if="item.title"
                            class="font-black"
                            :class="accentClasses.chip"
                        >
                            {{ item.title }} :
                        </span>
                        <span class="transition-all duration-300">
                            {{ item.body }}
                        </span>
                    </p>
                </button>
            </div>

            <div class="space-y-2 pt-1">
                <div
                    class="h-1.5 w-12 rounded-full"
                    :class="accentClasses.bar"
                ></div>
                <p
                    class="text-[11px] font-black tracking-[0.14em] text-black uppercase dark:text-white"
                >
                    {{ footer }}
                </p>
            </div>
        </div>
    </section>
</template>
