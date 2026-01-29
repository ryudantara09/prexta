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

const newDate = ref('');

// Initialize newDate when dialog opens or event changes
import { watch } from 'vue';
watch(() => props.event, (val) => {
    if (val && val.start) {
         const date = new Date(val.start);
         const offset = date.getTimezoneOffset() * 60000;
         newDate.value = (new Date(date.getTime() - offset)).toISOString().slice(0, 16);
    }
}, { immediate: true });

const handleConfirm = () => {
    emit('confirm', newDate.value);
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="handleOpenUpdate">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle class="text-xl">Confirm Reschedule</DialogTitle>
                <DialogDescription class="pt-4" v-if="event">
                    <p class="mb-4 text-base">
                        Are you sure you want to reschedule the interview with 
                        <strong class="text-foreground">{{ event.extendedProps?.applicant || 'the applicant' }}</strong>?
                    </p>
                    
                    <div class="flex flex-col items-center justify-center rounded-lg border border-border bg-muted/50 p-6">
                        <div class="mb-2 text-sm font-medium uppercase tracking-wide text-muted-foreground">Confirm New Time</div>
                        <input
                            type="datetime-local"
                            v-model="newDate"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-center font-bold text-lg ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        />
                         <div class="mt-2 text-sm text-muted-foreground" v-if="newDate">
                             {{ new Intl.DateTimeFormat('en-US', { weekday: 'long', month: 'long', day: 'numeric' }).format(new Date(newDate)) }}
                        </div>
                    </div>
                </DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <Button variant="ghost" @click="close">Cancel</Button>
                <Button @click="handleConfirm">Confirm New Time</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
