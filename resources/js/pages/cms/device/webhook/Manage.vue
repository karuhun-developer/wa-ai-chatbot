<script setup lang="ts">
import { sync } from '@/actions/App/Http/Controllers/Cms/Device/DeviceWebhookController';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useSwal } from '@/composables/useSwal';
import { DeviceDataItem, DeviceWebhookDataItem } from '@/types/cms/device';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';

const props = defineProps<{
    device: DeviceDataItem;
    webhooks: Record<string, DeviceWebhookDataItem>;
}>();

const { toast } = useSwal();

// Define available webhook events
const webhookEvents = [
    {
        event: 'All',
        label: 'All Events',
        description: 'Triggered for all events',
    },
    {
        event: 'MessageSent',
        label: 'Message Sent',
        description: 'Triggered when a message is sent',
    },
    {
        event: 'MessageReceived',
        label: 'Message Received',
        description: 'Triggered when a message is received',
    },
    {
        event: 'MessageRead',
        label: 'Message Read',
        description: 'Triggered when a message is read',
    },
    {
        event: 'MessageDeleted',
        label: 'Message Deleted',
        description: 'Triggered when a message is deleted',
    },
];

// Get initial values for each event
const getInitialWebhook = (event: string) => {
    return props.webhooks[event] || { event, url: '', status: false };
};
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Manage Webhooks - {{ device.name }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Configure webhook URLs for different events. Leave URL empty to
                disable a webhook.
            </p>

            <Form
                v-bind="sync.form({ device: device.id })"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Webhooks updated.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div
                    v-for="(webhookEvent, index) in webhookEvents"
                    :key="webhookEvent.event"
                    class="space-y-2 rounded-lg border p-4"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <Label :for="`webhook-${index}-url`">
                                {{ webhookEvent.label }}
                            </Label>
                            <InputDescription>
                                {{ webhookEvent.description }}
                            </InputDescription>
                        </div>
                        <div class="flex items-center gap-2">
                            <Label
                                :for="`webhook-${index}-status`"
                                class="text-sm text-gray-500"
                            >
                                Active
                            </Label>
                            <Checkbox
                                :id="`webhook-${index}-status`"
                                :name="`webhooks[${index}][status]`"
                                :default-value="
                                    getInitialWebhook(webhookEvent.event).status
                                "
                                value="1"
                            />
                        </div>
                    </div>

                    <input
                        type="hidden"
                        :name="`webhooks[${index}][event]`"
                        :value="webhookEvent.event"
                    />

                    <Input
                        :id="`webhook-${index}-url`"
                        :name="`webhooks[${index}][url]`"
                        type="url"
                        placeholder="https://example.com/webhook"
                        class="mt-2 block w-full"
                        :default-value="
                            getInitialWebhook(webhookEvent.event).url || ''
                        "
                    />
                    <InputError :message="errors[`webhooks.${index}.url`]" />
                </div>

                <div class="flex justify-end gap-4">
                    <Button :disabled="processing" type="submit">
                        <Save class="mr-2 h-4 w-4" />
                        Save Webhooks
                    </Button>
                </div>
            </Form>
        </div>
    </Modal>
</template>
