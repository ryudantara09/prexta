<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ExternalLink, Copy, Trash2 } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { cancel } from '@/routes/dashboard/interviews';
import { router } from '@inertiajs/vue3';

defineProps<{
    pendingInterviews: Array<{
        id: number;
        token: string;
        created_at: string;
        url: string;
        person: string;
        ttl: string;
    }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Interviews',
        href: '/dashboard/interviews',
    },
    {
        title: 'Pending Links',
        href: '/dashboard/interviews/pending',
    },
];

const copyLink = (url: string) => {
    navigator.clipboard.writeText(url);
    toast.success('Link copied to clipboard!');
};

const deleteLink = (id: number) => {
    if (confirm('Are you sure you want to delete this link?')) {
        router.post(cancel.url(id), {}, {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Link removed successfully');
            },
            onError: () => {
                toast.error('Failed to remove link');
            }
        });
    }
};
</script>

<template>
    <Head title="Pending Interview Links" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        Pending Interview Links
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Generated links that haven't been booked yet.
                    </p>
                </div>
                 <Link href="/dashboard/interviews">
                    <Button variant="outline">Back to Calendar</Button>
                </Link>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Person/Entity</TableHead>
                            <TableHead>Link</TableHead>
                            <TableHead>Generated At</TableHead>
                            <TableHead>TTL</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="interview in pendingInterviews" :key="interview.id">
                            <TableCell class="font-medium">
                                {{ interview.person }}
                            </TableCell>
                            <TableCell>
                                <a :href="interview.url" target="_blank" class="text-primary hover:underline max-w-[300px] truncate block" :title="interview.url">
                                    {{ interview.url }}
                                </a>
                            </TableCell>
                            <TableCell>{{ interview.created_at }}</TableCell>
                            <TableCell>{{ interview.ttl }}</TableCell>
                             <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                     <Button variant="outline" size="sm" @click="copyLink(interview.url)" title="Copy Link" class="gap-2">
                                        <Copy class="h-4 w-4" />
                                        Copy Link
                                    </Button>
                                    <Button variant="destructive" size="sm" @click="deleteLink(interview.id)" title="Delete Link">
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="pendingInterviews.length === 0">
                            <TableCell colspan="5" class="h-24 text-center">
                                No pending interview links found.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
