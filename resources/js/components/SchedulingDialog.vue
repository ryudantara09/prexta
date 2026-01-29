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
import { Calendar as CalendarIcon, Link as LinkIcon } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { ref } from 'vue';

const props = defineProps<{
    applicationId: number | null;
    applicantName: string;
    isOpen: boolean;
}>();

const emit = defineEmits(['update:isOpen', 'linkGenerated']);

const mode = ref<'choice' | 'manual'>('choice');

const manualForm = useForm({
    scheduled_at: '',
});

const close = () => {
    emit('update:isOpen', false);
    mode.value = 'choice';
    manualForm.reset();
};

const handleGenerateLink = () => {
    emit('linkGenerated', props.applicationId);
    close();
};

const handleManualSchedule = () => {
    if (!props.applicationId) return;

    manualForm.post(manual.url(props.applicationId), {
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
                <DialogTitle>Schedule Interview</DialogTitle>
                <DialogDescription>
                    Choose how you want to schedule the interview for
                    <strong>{{ applicantName }}</strong
                    >.
                </DialogDescription>
            </DialogHeader>

            <div v-if="mode === 'choice'" class="grid gap-4 py-4">
                <Button
                    variant="outline"
                    class="flex h-20 flex-col items-center justify-center gap-2 border-2 transition-all hover:border-primary hover:bg-primary/5"
                    @click="handleGenerateLink"
                >
                    <LinkIcon class="h-6 w-6 text-primary" />
                    <span>Generate Booking Link</span>
                </Button>

                <Button
                    variant="outline"
                    class="flex h-20 flex-col items-center justify-center gap-2 border-2 transition-all hover:border-primary hover:bg-primary/5"
                    @click="mode = 'manual'"
                >
                    <CalendarIcon class="h-6 w-6 text-primary" />
                    <span>Schedule Manually</span>
                </Button>
            </div>

            <div v-if="mode === 'manual'" class="grid gap-4 py-4">
                <div class="grid gap-2">
                    <Label for="scheduled_at">Date & Time</Label>
                    <Input
                        id="scheduled_at"
                        type="datetime-local"
                        v-model="manualForm.scheduled_at"
                        class="w-full"
                    />
                    <p
                        v-if="manualForm.errors.scheduled_at"
                        class="text-xs text-red-500"
                    >
                        {{ manualForm.errors.scheduled_at }}
                    </p>
                </div>
            </div>

            <DialogFooter v-if="mode === 'manual'">
                <Button variant="ghost" @click="mode = 'choice'">Back</Button>
                <Button
                    :disabled="manualForm.processing"
                    @click="handleManualSchedule"
                >
                    Confirm Schedule
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
