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
import { onMounted, ref } from 'vue';

const props = defineProps<{
    isOpen: boolean;
    event: any;
}>();

const emit = defineEmits(['update:isOpen', 'confirm', 'cancel']);

const close = () => {
    emit('cancel');
};

const confirm = () => {
    emit('confirm');
};

// We can just use the dialog's update:open to handle outside clicks or escapes as cancels
const handleOpenUpdate = (val: boolean) => {
    if (!val) {
        close();
    }
};

</script>

<template>
    <Dialog :open="isOpen" @update:open="handleOpenUpdate">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Confirm Reschedule</DialogTitle>
                <DialogDescription v-if="event">
                    Are you sure you want to reschedule the interview with 
                    <strong>{{ event.extendedProps?.applicant || 'the applicant' }}</strong> to:
                    <div class="mt-2 rounded-md bg-muted p-3 text-center text-lg font-medium text-foreground">
                        {{ new Date(event.start).toLocaleString() }}
                    </div>
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button variant="ghost" @click="close">Cancel</Button>
                <Button @click="confirm">Confirm New Time</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
