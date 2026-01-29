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
import { cancel, update as updateRoute } from '@/routes/dashboard/interviews';
import { useForm, router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { ref, watch, computed } from 'vue';

const props = defineProps<{
    isOpen: boolean;
    event: any;
}>();

const emit = defineEmits(['update:isOpen']);

const close = () => {
    emit('update:isOpen', false);
};

const form = useForm({
    title: '', // Only for non-applications
    guest_name: '', // Only for non-applications
    scheduled_at: '',
    status: '',
});

// Watch for event changes to initialize form
watch(() => props.event, (newEvent) => {
    if (newEvent) {
        // Init Time
        if (newEvent.start) {
            const date = new Date(newEvent.start);
            const offset = date.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(date.getTime() - offset)).toISOString().slice(0, 16);
            form.scheduled_at = localISOTime;
        }

        // Init Status
        form.status = newEvent.extendedProps?.status || 'scheduled';

        // Init Fields
        if (newEvent.extendedProps?.is_application) {
            // Read-only logic handles display, but form state might not need these
            // unless we submitted them, but we only submit editable fields.
            form.title = '';
            form.guest_name = '';
        } else {
            form.title = newEvent.title || '';
            form.guest_name = newEvent.extendedProps?.guest_name || ''; // Assuming we added guest_name to extendedProps via controller
            // Actually, in our controller earlier, I used 'applicant' prop for guest name too in the mapping.
            // Let's rely on the distinct props I added: guest_name, meeting_title
            
            // From controller update:
            // 'meeting_title' => $interview->meeting_title,
            // 'guest_name' => $interview->guest_name,
            
            form.title = newEvent.extendedProps?.meeting_title || newEvent.title; 
            form.guest_name = newEvent.extendedProps?.guest_name || newEvent.extendedProps?.applicant;
        }
    }
}, { immediate: true });

const handleCancel = () => {
    if (!confirm('Are you sure you want to cancel this interview?')) return;

    router.post(cancel.url(props.event.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Interview cancelled successfully');
            close();
        },
        onError: () => {
             toast.error('Failed to cancel interview');
        }
    });
};

const handleUpdate = () => {
    form.processing = true;
    router.patch(updateRoute.url(props.event.id), {
        scheduled_at: form.scheduled_at,
        status: form.status,
        meeting_title: props.event.extendedProps?.is_application ? null : form.title,
        guest_name: props.event.extendedProps?.is_application ? null : form.guest_name,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Interview updated successfully');
            close();
        },
        onError: (errors) => {
             const message = Object.values(errors)[0] || 'Failed to update interview';
             toast.error(message as string);
        },
        onFinish: () => form.processing = false,
    });
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="close">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Interview Details</DialogTitle>
                <DialogDescription>
                    Update the details of the scheduled interview.
                </DialogDescription>
            </DialogHeader>

            <div class="grid gap-4 py-4" v-if="event">
                
                <!-- Title Field -->
                <div class="grid gap-2">
                    <Label for="title">Title</Label>
                    <Input
                        v-if="!event.extendedProps?.is_application"
                        id="title"
                        v-model="form.title"
                    />
                    <div v-else class="rounded-md border bg-muted px-3 py-2 text-sm text-muted-foreground">
                        {{ event.title }}
                    </div>
                </div>

                <!-- Applicant/Guest Field -->
                <div class="grid gap-2">
                    <Label for="person">{{ event.extendedProps?.is_application ? 'Applicant' : 'Guest Name' }}</Label>
                    <Input
                        v-if="!event.extendedProps?.is_application"
                        id="person"
                        v-model="form.guest_name"
                    />
                     <div v-else class="rounded-md border bg-muted px-3 py-2 text-sm text-muted-foreground">
                        {{ event.extendedProps?.applicant }}
                    </div>
                </div>
                 
                 <!-- Job Field (Always Read-only) -->
                 <div class="grid gap-2">
                    <Label>Job</Label>
                    <div class="rounded-md border bg-muted px-3 py-2 text-sm text-muted-foreground">
                        {{ event.extendedProps?.job || 'N/A' }}
                    </div>
                </div>

                <!-- Time Field -->
                 <div class="grid gap-2">
                    <Label for="scheduled_at">Date & Time</Label>
                    <Input
                        id="scheduled_at"
                        type="datetime-local"
                        v-model="form.scheduled_at"
                    />
                </div>

                <!-- Status Field -->
                 <div class="grid gap-2">
                    <Label for="status">Status</Label>
                    <select
                        id="status"
                        v-model="form.status"
                         class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none dark:bg-gray-800"
                    >
                        <option value="pending">Pending</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

            </div>

            <DialogFooter class="gap-2 sm:gap-0">
                <Button variant="destructive" class="mr-auto" @click="handleCancel">
                    Cancel Interview
                </Button>
                <Button variant="secondary" @click="close">Close</Button>
                <Button @click="handleUpdate" :disabled="form.processing || !form.isDirty">
                    Save Changes
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
