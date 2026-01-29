<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { manual } from '@/routes/dashboard/applications/interview';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { watch } from 'vue';

const props = defineProps<{
    applications: Array<{ id: number; name: string }>;
    isOpen: boolean;
    initialDate?: string;
}>();

const emit = defineEmits(['update:isOpen']);

const form = useForm({
    application_id: '',
    scheduled_at: '',
});

watch(
    [() => props.initialDate, () => props.isOpen],
    ([newDate, newOpen]) => {
        if (newOpen && newDate) {
            // Ensure format is compatible with datetime-local (YYYY-MM-DDTHH:mm)
            // If newDate is just YYYY-MM-DD, append T09:00
            if (newDate.includes('T')) {
                 form.scheduled_at = newDate.slice(0, 16);
            } else {
                 form.scheduled_at = `${newDate}T09:00`;
            }
        }
    },
    { immediate: true }
);

const close = () => {
    emit('update:isOpen', false);
    form.reset();
};

const handleSchedule = () => {
    if (!form.application_id) return;

    form.post(manual.url(form.application_id), {
        preserveScroll: true,
        onSuccess: (page) => {
            const flash = page.props.flash as any;
            toast.success(flash?.message || 'Interview scheduled successfully!');
            close();
        },
    });
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="close">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Schedule New Interview</DialogTitle>
                <DialogDescription>
                    Select an applicant and a date/time for the interview.
                </DialogDescription>
            </DialogHeader>

            <div class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="application">Applicant</Label>
                    <select
                        id="application"
                        v-model="form.application_id"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:bg-gray-800"
                    >
                        <option value="" disabled>Select an applicant</option>
                        <option
                            v-for="app in applications"
                            :key="app.id"
                            :value="app.id"
                        >
                            {{ app.name }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.application_id"
                        class="text-xs text-red-500"
                    >
                        {{ form.errors.application_id }}
                    </p>
                </div>

                <div class="grid gap-2">
                    <Label for="scheduled_at">Date & Time</Label>
                    <Input
                        id="scheduled_at"
                        type="datetime-local"
                        v-model="form.scheduled_at"
                    />
                    <p
                        v-if="form.errors.scheduled_at"
                        class="text-xs text-red-500"
                    >
                        {{ form.errors.scheduled_at }}
                    </p>
                </div>
            </div>

            <DialogFooter>
                <Button variant="ghost" @click="close">Cancel</Button>
                <Button
                    :disabled="
                        form.processing ||
                        !form.application_id ||
                        !form.scheduled_at
                    "
                    @click="handleSchedule"
                >
                    Schedule Interview
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
