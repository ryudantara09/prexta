<script setup lang="ts">
import { index as jobsIndex } from '@/actions/App/Http/Controllers/PublicJobController';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InsightFlowCard from '@/components/welcome/InsightFlowCard.vue';
import SectorCard from '@/components/welcome/SectorCard.vue';
import { Form, Head, Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

type SectorItem = {
    title: string;
    description: string;
    icon: string;
    color: 'prexta-blue' | 'prexta-cyan' | 'prexta-indigo';
};

type InsightItem = {
    title?: string;
    body: string;
};

const sectors: SectorItem[] = [
    {
        title: "Architecture d'Entreprise & Cadrage Stratégique",
        description:
            "Dans des environnements SI complexes et hétérogènes, les décisions techniques engagent durablement les organisations. Prexta accompagne ses clients afin de :\n● Analyser l'existant et identifier les fragilités structurelles\n● Définir une cible réaliste, priorisée et gouvernée\n● Arbitrer entre contraintes métiers, techniques, financières et réglementaires\n● sécuriser les investissements IT et les trajectoires de transformation",
        icon: 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        color: 'prexta-blue',
    },
    {
        title: "Cybersécurité & Résilience des Systèmes d'Information",
        description:
            "L'augmentation des menaces cyber et la complexité des environnements IT exigent des dispositifs de sécurité robustes et opérationnels. Prexta accompagne les organisations pour :\n● Renforcer leur posture de sécurité globale\n● Protéger les infrastructures et données critiques\n● Améliorer la capacité de détection et de réponse aux incidents\n● Structurer une gouvernance cyber efficace",
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        color: 'prexta-cyan',
    },
    {
        title: 'Cloud, Infrastructures & Exploitation IT',
        description:
            "Les infrastructures et environnements cloud sont au cœur des systèmes d'information modernes. Prexta accompagne ses clients pour :\n● Concevoir des environnements adaptés aux enjeux métiers\n● Sécuriser l'exploitation et la continuité de service\n● Piloter des projets techniques complexes\n● Maîtriser les coûts, délais et risques opérationnels",
        icon: 'M13 10V3L4 14h7v7l9-11h-7z',
        color: 'prexta-indigo',
    },
    {
        title: 'Data, Gouvernance & Intelligence Artificielle',
        description:
            'La donnée est un actif stratégique, mais souvent sous-exploité. Prexta accompagne ses clients pour :\n● Structurer leurs plateformes data\n● Fiabiliser et gouverner les données\n● Généraliser les usages IA au sein du SI\n● Intégrer et piloter les agents IA (Protocole A2A)\n● Sécuriser les flux et les traitements de données sensibles\n● MoM et MoF',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        color: 'prexta-blue',
    },
];

const approachItems: InsightItem[] = [
    {
        title: 'Compréhension & qualification du besoin',
        body: 'analyse du SI existant, enjeux métiers et risques.',
    },
    {
        title: 'Cadrage & trajectoire',
        body: "définition d'une cible réaliste et priorisée.",
    },
    {
        title: 'Arbitrage & gouvernance',
        body: "aide à la décision et comités d'architecture.",
    },
    {
        title: 'Mise en œuvre sécurisée',
        body: 'déclinaison via IA Ready, Cloud, Cyber, Data ou Ops.',
    },
];

const methodItems: InsightItem[] = [
    {
        body: 'Vision transverse Métier / IT / Sécurité.',
    },
    {
        body: 'Approche orientée décision, vision indépendante.',
    },
    {
        body: 'Culture du résultat maîtrisé (forfait, conseil et assistance technique).',
    },
];

onMounted(() => {
    // Reveal Observer - Triggers when entering/leaving viewport
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-active');
                } else {
                    entry.target.classList.remove('reveal-active');
                }
            });
        },
        {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px',
        },
    );

    document.querySelectorAll('.reveal').forEach((el) => {
        revealObserver.observe(el);
    });
});
</script>

<template>
    <Head title="Prexta | Conseil & Stratégie">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="relative min-h-screen overflow-hidden bg-white text-[#1b1b18] selection:bg-prexta-blue selection:text-white dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
    >
        <!-- Background Decor -->
        <div
            class="pointer-events-none absolute -top-48 -left-48 h-[600px] w-[600px] rounded-full bg-prexta-blue/10 blur-[140px] dark:bg-prexta-blue/5"
        ></div>
        <div
            class="pointer-events-none absolute top-1/2 -right-48 h-[700px] w-[700px] -translate-y-1/2 rounded-full bg-prexta-cyan/10 blur-[160px] dark:bg-prexta-cyan/5"
        ></div>

        <!-- Header -->
        <header
            class="relative z-50 mx-auto flex max-w-7xl items-center justify-between px-6 py-4"
        >
            <div class="reveal reveal-fade flex items-center gap-2">
                <div
                    class="h-8 w-8 rounded-lg bg-prexta-gradient shadow-lg"
                ></div>
                <span class="text-xl font-black tracking-tighter">PREXTA</span>
            </div>

            <nav class="hidden items-center gap-8 md:flex">
                <a
                    href="#about"
                    class="reveal reveal-fade-up text-xs font-black tracking-widest uppercase transition-colors hover:text-prexta-blue"
                    style="--delay: 100ms"
                    >À Propos</a
                >
                <a
                    href="#sectors"
                    class="reveal reveal-fade-up text-xs font-black tracking-widest uppercase transition-colors hover:text-prexta-blue"
                    style="--delay: 200ms"
                    >Expertise</a
                >
                <Link
                    :href="jobsIndex.url()"
                    class="reveal reveal-fade-up text-xs font-black tracking-widest uppercase transition-colors hover:text-prexta-blue"
                    style="--delay: 300ms"
                    >Carrières</Link
                >
                <a
                    href="#contact"
                    class="reveal reveal-fade-up text-xs font-black tracking-widest uppercase transition-colors hover:text-prexta-blue"
                    style="--delay: 400ms"
                    >Contact</a
                >

                <div
                    class="reveal reveal-fade h-4 w-px bg-gray-200 dark:bg-gray-800"
                    style="--delay: 500ms"
                ></div>

                <div
                    class="reveal reveal-fade flex items-center"
                    style="--delay: 700ms"
                >
                    <AppearanceTabs />
                </div>
            </nav>

            <div class="reveal reveal-fade flex items-center gap-4 md:hidden">
                <AppearanceTabs />
                <button
                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg bg-gray-100 transition-transform active:scale-90 dark:bg-gray-800"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"
                        />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Hero Section -->
        <main
            class="relative z-10 mx-auto flex max-w-6xl flex-col items-center px-6 pt-10 pb-14 text-center lg:pt-14 lg:pb-20"
        >
            <h1
                class="mt-4 flex flex-col items-center gap-2 text-5xl leading-[0.9] font-black tracking-[-0.05em] sm:text-7xl lg:text-[7rem]"
            >
                <span class="reveal reveal-fade-up" style="--delay: 100ms"
                    >Tech With A</span
                >
                <span
                    class="text-gradient reveal reveal-scale-up"
                    style="--delay: 300ms"
                    >Human Heartbeat</span
                >
            </h1>

            <p
                class="reveal reveal-fade-up mt-8 max-w-2xl text-base leading-relaxed font-medium text-gray-600 md:text-xl dark:text-gray-400"
                style="--delay: 400ms"
            >
                Nous intervenons là où les projets critiques nécessitent une
                vision globale, des arbitrages structurants et une gouvernance
                solide pour sécuriser les investissements IT, maîtriser les
                risques et garantir la réussite des transformations.
            </p>

            <div
                class="reveal reveal-fade-up mt-10 flex flex-col items-center gap-4 sm:flex-row"
                style="--delay: 600ms"
            >
                <Link
                    :href="jobsIndex.url()"
                    class="group relative overflow-hidden rounded-full bg-[#1b1b18] px-8 py-4 text-base font-black text-white shadow-xl transition-all hover:scale-105 active:scale-95 dark:bg-white dark:text-black"
                >
                    <span class="relative z-10">REJOINDRE L'AVENTURE</span>
                    <div
                        class="absolute inset-0 bg-prexta-gradient opacity-0 transition-opacity group-hover:opacity-100"
                    ></div>
                </Link>
                <a
                    href="#sectors"
                    class="rounded-full border-2 border-gray-100 bg-white/50 px-8 py-4 text-base font-black backdrop-blur-sm transition-all hover:bg-gray-50 active:scale-95 dark:border-gray-800 dark:bg-black/50 dark:hover:bg-gray-900"
                >
                    NOS EXPERTISES
                </a>
            </div>
        </main>

        <!-- À Propos -->
        <section
            id="about"
            class="relative z-10 border-y border-gray-100 bg-gray-50/30 px-6 py-16 dark:border-gray-950 dark:bg-gray-950/20"
        >
            <div class="mx-auto max-w-7xl">
                <div class="grid items-center gap-10 lg:grid-cols-2">
                    <div class="relative">
                        <div
                            class="reveal reveal-fade absolute -top-12 -left-12 h-64 w-64 rounded-full bg-prexta-gradient opacity-10 blur-3xl"
                            style="--duration: 2s"
                        ></div>
                        <h2
                            class="reveal reveal-slide-left mb-6 text-4xl leading-none font-black tracking-tighter lg:text-6xl"
                        >
                            Qui sommes <br /><span class="text-gradient"
                                >nous ?</span
                            >
                        </h2>
                        <div
                            class="reveal reveal-slide-left space-y-4 text-base leading-relaxed text-gray-600 dark:text-gray-400"
                            style="--delay: 200ms"
                        >
                            <p>
                                <strong>Prexta</strong> un cabinet de conseil
                                premium spécialisé en Architecture d'Entreprise,
                                Architectures IT, Transformation Digitale et
                                Ingénierie (Data, Cyber, Cloud, Ops, IA),
                                apportant expertise, séniorité et éthique.
                            </p>
                            <p>
                                Nous accompagnons les organisations dans la
                                conception, la transformation et la gouvernance
                                des systèmes d'information stratégiques, en
                                plaçant l'architecture, l'IA et l'ingénierie au
                                cœur des décisions.
                            </p>
                        </div>

                        <div class="mt-8 grid grid-cols-2 gap-6">
                            <div
                                class="reveal reveal-fade-up"
                                style="--delay: 300ms"
                            >
                                <div
                                    class="animate-[pulse_4s_infinite] text-3xl font-black text-prexta-blue"
                                >
                                    150+
                                </div>
                                <div
                                    class="mt-2 text-[10px] font-black tracking-[0.2em] text-gray-400 uppercase"
                                >
                                    Succès Partagés
                                </div>
                            </div>
                            <div
                                class="reveal reveal-fade-up"
                                style="--delay: 400ms"
                            >
                                <div
                                    class="animate-[pulse_4s_infinite] text-3xl font-black text-prexta-cyan"
                                    style="animation-delay: 1s"
                                >
                                    12
                                </div>
                                <div
                                    class="mt-2 text-[10px] font-black tracking-[0.2em] text-gray-400 uppercase"
                                >
                                    Pôles d'Excellence
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="reveal reveal-scale-up grid grid-cols-1 gap-3 xl:grid-cols-2"
                        style="--delay: 400ms"
                    >
                        <InsightFlowCard
                            heading="Notre approche"
                            :items="approachItems"
                            footer="Notre approche"
                            accent="blue"
                        />

                        <InsightFlowCard
                            :items="methodItems"
                            footer="Éclairer les décisions IT. Structurer les trajectoires. Réussir les projets complexes."
                            accent="cyan"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Expertise -->
        <section id="sectors" class="relative z-10 px-6 py-16">
            <div class="mx-auto max-w-7xl">
                <div class="reveal reveal-fade-up mb-10 text-center">
                    <h2
                        class="text-4xl font-black tracking-tighter lg:text-6xl"
                    >
                        Notre <span class="text-gradient">ADN.</span>
                    </h2>
                    <p
                        class="mx-auto mt-4 max-w-2xl text-lg font-medium text-gray-400"
                    >
                        Quatre piliers fondamentaux pour une transformation sans
                        compromis.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <SectorCard
                        v-for="(sector, index) in sectors"
                        :key="sector.title"
                        :sector="sector"
                        :index="index"
                    />
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="relative z-10 px-6 py-14">
            <div
                class="reveal reveal-scale-up relative mx-auto max-w-6xl overflow-hidden rounded-[3rem] bg-[#1b1b18] px-8 py-16 text-center shadow-[0_48px_96px_-32px_rgba(0,0,0,0.4)] sm:px-12"
            >
                <div
                    class="absolute inset-0 bg-prexta-gradient opacity-[0.03]"
                ></div>
                <div
                    class="absolute -top-48 -right-48 h-96 w-96 rounded-full bg-prexta-blue/5 blur-[120px]"
                ></div>

                <h2
                    class="reveal reveal-fade-up mb-7 text-4xl leading-none font-black text-white md:text-6xl"
                >
                    Prêt pour le <br /><span class="text-gradient">Jump ?</span>
                </h2>
                <div
                    class="reveal reveal-fade-up flex flex-col justify-center gap-6 sm:flex-row"
                    style="--delay: 200ms"
                >
                    <a
                        href="#contact"
                        class="rounded-full bg-white px-8 py-4 text-base font-black text-black transition-all hover:scale-105 hover:shadow-2xl"
                    >
                        START THE AUDIT
                    </a>
                    <Link
                        :href="jobsIndex.url()"
                        class="rounded-full border-2 border-white/10 px-8 py-4 text-base font-black text-white transition-all hover:bg-white/5"
                    >
                        JOIN THE CREW
                    </Link>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="relative z-10 overflow-hidden px-6 py-16">
            <div class="mx-auto max-w-7xl">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div class="reveal reveal-slide-left">
                        <h2
                            class="mb-6 text-4xl leading-none font-black tracking-tighter lg:text-6xl"
                        >
                            Contactez <br /><span class="text-gradient"
                                >nous.</span
                            >
                        </h2>
                        <p
                            class="mb-8 max-w-md text-lg leading-relaxed text-gray-500"
                        >
                            Une question ? Un projet ? Notre équipe d'experts
                            est à votre écoute pour propulser votre innovation.
                        </p>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-prexta-blue/10 text-prexta-blue"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <span class="text-lg font-bold"
                                    >contact@prexta.com</span
                                >
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-prexta-cyan/10 text-prexta-cyan"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <span class="text-lg font-bold">Paris</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="reveal reveal-scale-up relative"
                        style="--delay: 200ms"
                    >
                        <div
                            class="absolute -inset-4 rounded-[3rem] bg-prexta-gradient opacity-10 blur-3xl"
                        ></div>
                        <div
                            class="relative rounded-[2rem] border border-gray-100 bg-white p-6 shadow-2xl sm:p-8 dark:border-gray-800 dark:bg-gray-900"
                        >
                            <Form
                                action="/contact"
                                method="post"
                                #default="{ errors, processing, wasSuccessful }"
                            >
                                <div class="space-y-6">
                                    <div class="space-y-2">
                                        <Label
                                            for="name"
                                            class="text-[10px] font-black tracking-widest text-gray-400 uppercase"
                                            >Nom Complet</Label
                                        >
                                        <Input
                                            id="name"
                                            name="name"
                                            placeholder="Votre nom"
                                            required
                                        />
                                        <InputError :message="errors.name" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label
                                            for="email"
                                            class="text-[10px] font-black tracking-widest text-gray-400 uppercase"
                                            >Email</Label
                                        >
                                        <Input
                                            id="email"
                                            name="email"
                                            type="email"
                                            placeholder="votre@email.com"
                                            required
                                        />
                                        <InputError :message="errors.email" />
                                    </div>
                                    <div class="space-y-2">
                                        <Label
                                            for="message"
                                            class="text-[10px] font-black tracking-widest text-gray-400 uppercase"
                                            >Message</Label
                                        >
                                        <textarea
                                            id="message"
                                            name="message"
                                            rows="4"
                                            placeholder="Comment pouvons-nous vous aider ?"
                                            class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:bg-input/30"
                                            required
                                        ></textarea>
                                        <InputError :message="errors.message" />
                                    </div>

                                    <Button
                                        type="submit"
                                        :disabled="processing"
                                        class="group w-full rounded-2xl bg-[#1b1b18] py-6 text-lg font-black transition-transform hover:scale-[1.02] active:scale-95 dark:bg-white dark:text-black"
                                    >
                                        <span v-if="!processing">ENVOYER</span>
                                        <span v-else>ENVOI...</span>
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                    </Button>

                                    <p
                                        v-if="wasSuccessful"
                                        class="mt-4 text-center text-sm font-bold text-green-500"
                                    >
                                        Message envoyé avec succès !
                                    </p>
                                </div>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer
            class="relative z-10 border-t border-gray-100 bg-white px-6 pt-16 pb-10 dark:border-gray-900 dark:bg-black"
        >
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 grid gap-10 lg:grid-cols-4">
                    <div class="lg:col-span-2">
                        <div class="mb-8 flex items-center gap-2">
                            <div
                                class="h-8 w-8 rounded bg-prexta-gradient"
                            ></div>
                            <span
                                class="text-2xl font-black tracking-tighter uppercase"
                                >Prexta</span
                            >
                        </div>
                        <p
                            class="max-w-sm leading-relaxed font-medium text-gray-500"
                        >
                            Architectes de la transformation. Nous bâtissons les
                            infrastructures de demain.
                        </p>
                    </div>

                    <div>
                        <h4
                            class="mb-6 text-[10px] font-black tracking-[0.3em] text-gray-300 uppercase"
                        >
                            Navigation
                        </h4>
                        <ul class="space-y-4 text-sm font-bold">
                            <li>
                                <a
                                    href="#"
                                    class="transition-colors hover:text-prexta-blue"
                                    >Accueil</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#about"
                                    class="transition-colors hover:text-prexta-blue"
                                    >À Propos</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#sectors"
                                    class="transition-colors hover:text-prexta-blue"
                                    >Expertise</a
                                >
                            </li>
                            <li>
                                <Link
                                    :href="jobsIndex.url()"
                                    class="transition-colors hover:text-prexta-blue"
                                    >Carrières</Link
                                >
                            </li>
                            <li>
                                <a
                                    href="#contact"
                                    class="transition-colors hover:text-prexta-blue"
                                    >Contact</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between border-t border-gray-50 pt-8 text-[10px] font-black tracking-[0.3em] text-gray-400 uppercase dark:border-gray-900"
                >
                    <span>&copy; PREXTA {{ new Date().getFullYear() }}</span>
                </div>
            </div>
        </footer>
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

/* --- REVEAL SYSTEM (ONE-TIME) --- */
.reveal {
    opacity: 0;
    transition-duration: var(--duration, 1.2s);
    transition-delay: var(--delay, 0ms);
    transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    will-change: transform, opacity;
}

/* The state that remains after scrolling */
.reveal-active {
    opacity: 1 !important;
    transform: none !important;
}

/* Animations Variants */
.reveal-fade-up {
    transform: translateY(40px);
}
.reveal-scale-up {
    transform: scale(0.8) translateY(40px);
}
.reveal-slide-left {
    transform: translateX(-40px);
}
.reveal-slide-right {
    transform: translateX(40px);
}
.reveal-fade {
    /* opacity only */
}

/* Prevent extra animations / Ensure stickiness */
.reveal-active.reveal {
    transition:
        opacity 1.2s ease,
        transform 1.2s ease;
}

html {
    scroll-behavior: smooth;
}

body {
    overflow-x: hidden;
}

/* Hide scrollbar but keep functionality for a cleaner 'app' feel */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    background: transparent;
}
::-webkit-scrollbar-thumb {
    background: #e2e2e2;
    border-radius: 10px;
}
.dark ::-webkit-scrollbar-thumb {
    background: #333;
}
</style>
