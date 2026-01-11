<script setup lang="ts">
import { store } from '@/actions/App/Http/Controllers/Cms/Device/DeviceController';
import InputDescription from '@/components/InputDescription.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useSwal } from '@/composables/useSwal';
import { Form } from '@inertiajs/vue3';
import { Modal } from '@inertiaui/modal-vue';
import { Save } from 'lucide-vue-next';

const { toast } = useSwal();
</script>

<template>
    <Modal v-slot="{ close }">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Create Device
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Create a new device that can connect to WhatsApp.
            </p>

            <Form
                v-bind="store.form()"
                class="mt-6 space-y-6"
                @success="
                    () => {
                        toast.fire({
                            icon: 'success',
                            title: 'Device created.',
                        });
                        close();
                    }
                "
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <InputDescription>
                        Device name (e.g., 'Device 1', 'Office Laptop').
                    </InputDescription>
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autofocus
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="flex justify-end gap-4">
                    <Button :disabled="processing" type="submit">
                        <Save />
                        Save Changes
                    </Button>
                </div>
            </Form>
        </div>
    </Modal>
</template>
