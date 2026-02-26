<script setup lang="ts">
import {
    index,
    save,
} from '@/actions/App/Http/Controllers/Cms/Setting/SettingController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Setting } from '@/types/cms/setting';
import { Form, Head } from '@inertiajs/vue3';

const props = defineProps<{
    setting: Setting;
}>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Settings',
        href: index().url,
    },
];

const { toast } = useSwal();

const title = 'System Settings';
const description = 'Manage your system and AI configuration';
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="title" />
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <Heading :title="title" :description="description" />

            <Form
                v-bind="save.form()"
                :onSuccess="
                    () =>
                        toast.fire({
                            icon: 'success',
                            title: 'Settings saved successfully.',
                        })
                "
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="flex items-center justify-between border-b bg-card">
                    <div
                        class="flex w-full gap-6 overflow-x-auto px-6 md:w-auto"
                    >
                        <Button
                            type="button"
                            variant="ghost"
                            class="relative rounded-none px-0 py-4 text-sm font-medium whitespace-nowrap text-foreground hover:bg-transparent"
                        >
                            AI Configuration
                            <span
                                class="absolute right-0 bottom-0 left-0 h-0.5 bg-primary"
                            ></span>
                        </Button>
                    </div>

                    <div class="hidden px-6 py-2 md:block">
                        <Button :disabled="processing" size="default">
                            Save Settings
                        </Button>
                    </div>
                </div>

                <div class="space-y-4 rounded-lg border bg-card p-6">
                    <div class="grid gap-2">
                        <Label>System Prompt</Label>
                        <Textarea
                            name="value[system_prompt]"
                            :default-value="setting?.value?.system_prompt"
                            placeholder="Enter the main system prompt for the AI..."
                            rows="4"
                        />
                        <span
                            v-if="errors['value.system_prompt']"
                            class="text-sm text-destructive"
                        >
                            {{ errors['value.system_prompt'] }}
                        </span>
                    </div>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
