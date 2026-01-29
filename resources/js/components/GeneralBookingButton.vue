<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { storeGeneral } from '@/routes/dashboard/interviews';
import { router } from '@inertiajs/vue3';
import { Link as LinkIcon } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

defineProps<{
    variant?: 'default' | 'outline' | 'secondary' | 'ghost' | 'link';
    size?: 'default' | 'sm' | 'lg' | 'icon';
    title?: string;
}>();

const generateGeneralLink = () => {
    console.log('Generating general link...');
    router.post(
        storeGeneral.url(),
        { meeting_title: 'Personal Meeting' },
        {
            preserveScroll: true,
            onSuccess: (page) => {
                const flash = page.props.flash as any;
                if (flash && flash.data && flash.data.interview_url) {
                    navigator.clipboard.writeText(flash.data.interview_url);
                    toast.success(flash.message, {
                        description: flash.data.interview_url,
                        duration: 8000,
                    });
                } else {
                    console.error('Flash data missing or incorrect:', flash);
                    toast.error(
                        'Process completed, but could not find the link.',
                    );
                }
            },
        },
    );
};
</script>

<template>
    <Button
        v-bind="$attrs"
        :variant="variant || 'default'"
        :size="size || 'default'"
        @click="generateGeneralLink"
        class="gap-2"
    >
        <LinkIcon class="h-4 w-4" />
        <span>{{ title || 'Generate Booking Link' }}</span>
    </Button>
</template>
