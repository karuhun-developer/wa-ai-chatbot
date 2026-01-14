```
<script setup lang="ts">
import { index } from '@/actions/App/Http/Controllers/Cms/Device/DeviceController';
import { send } from '@/actions/App/Http/Controllers/Cms/Device/DeviceMessageController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { DeviceDataItem } from '@/types/cms/device';
import { Form, Head, router } from '@inertiajs/vue3';
import { ArrowLeft, Send } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    device: DeviceDataItem;
}>();

const { toast } = useSwal();

const breadcrumbItems: BreadcrumbItem[] = [
    { title: 'Devices', href: '/cms/device/devices' },
    { title: props.device.name, href: '#' },
    { title: 'Test Message', href: '#' },
];

const form = ref({
    type: 'text' as 'text' | 'image' | 'video' | 'button',
    buttons: [{ id: '1', text: 'Button 1' }],
});

const addButton = () => {
    form.value.buttons.push({
        id: String(form.value.buttons.length + 1),
        text: `Button ${form.value.buttons.length + 1}`,
    });
};

const removeButton = (index: number) => {
    form.value.buttons.splice(index, 1);
};

const handleSuccess = () => {
    toast.fire({
        icon: 'success',
        title: 'Message sent successfully!',
    });
};

const handleError = (errors: any) => {
    toast.fire({
        icon: 'error',
        title: 'Failed to send message',
        text: Object.values(errors).join(', '),
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Test Message" />
        <div class="p-6">
            <!-- Header with Back Button -->
            <div class="mb-6 flex items-center gap-3">
                <Button
                    variant="ghost"
                    size="icon"
                    @click="router.visit(index().url)"
                >
                    <ArrowLeft class="h-5 w-5" />
                </Button>
                <div>
                    <h1 class="text-xl font-semibold">Test Message</h1>
                    <p class="text-sm text-muted-foreground">
                        {{ device.name }} - Send test messages to WhatsApp
                        numbers
                    </p>
                </div>
            </div>

            <!-- Form -->
            <Form
                :action="send({ device: device.id }).url"
                method="post"
                class="mt-3 space-y-4"
                @success="handleSuccess"
                @error="handleError"
                v-slot="{ errors, processing }"
            >
                <!-- Phone Number -->
                <div>
                    <Label for="phone">Phone Number</Label>
                    <Input
                        id="phone"
                        name="phone"
                        type="number"
                        placeholder="e.g., 081234567890 or +6281234567890"
                        class="mt-1"
                        required
                    />
                    <p class="mt-1 text-xs text-muted-foreground">
                        Enter the recipient's phone number
                    </p>
                    <InputError :message="errors.phone" />
                </div>

                <!-- Message Type -->
                <div class="mt-3">
                    <Label for="type">Message Type</Label>
                    <Select name="type" v-model="form.type">
                        <SelectTrigger id="type" class="mt-1">
                            <SelectValue placeholder="Select message type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="text">Text Message</SelectItem>
                            <SelectItem value="image">Image Message</SelectItem>
                            <SelectItem value="video">Video Message</SelectItem>
                            <SelectItem value="button"
                                >Button Message</SelectItem
                            >
                        </SelectContent>
                    </Select>
                    <InputError :message="errors.type" />
                </div>

                <!-- Text Message -->
                <div v-if="form.type === 'text'" class="mt-3">
                    <Label for="message">Message</Label>
                    <textarea
                        id="message"
                        name="message"
                        placeholder="Enter your message..."
                        rows="5"
                        class="mt-1 flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                    />
                    <InputError :message="errors.message" />
                </div>

                <!-- Image Message -->
                <div v-if="form.type === 'image'" class="mt-3 space-y-4">
                    <div>
                        <Label for="image">Image File</Label>
                        <Input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/*"
                            class="mt-1"
                        />
                        <p class="mt-1 text-xs text-muted-foreground">
                            Upload an image file (max 2MB)
                        </p>
                        <InputError :message="errors.image" />
                    </div>
                    <div>
                        <Label for="caption">Caption (Optional)</Label>
                        <Input
                            id="caption"
                            name="caption"
                            type="text"
                            placeholder="Add a caption for your image..."
                            class="mt-1"
                        />
                        <InputError :message="errors.caption" />
                    </div>
                </div>

                <!-- Video Message -->
                <div v-if="form.type === 'video'" class="mt-3 space-y-4">
                    <div>
                        <Label for="video">Video File</Label>
                        <Input
                            id="video"
                            name="video"
                            type="file"
                            accept="video/*"
                            class="mt-1"
                        />
                        <p class="mt-1 text-xs text-muted-foreground">
                            Upload a video file (max 5MB)
                        </p>
                        <InputError :message="errors.video" />
                    </div>
                    <div class="mt-3">
                        <Label for="video-caption">Caption (Optional)</Label>
                        <Input
                            id="video-caption"
                            name="caption"
                            type="text"
                            placeholder="Add a caption for your video..."
                            class="mt-1"
                        />
                        <InputError :message="errors.caption" />
                    </div>
                </div>

                <!-- Button Message -->
                <div v-if="form.type === 'button'" class="mt-3 space-y-4">
                    <div>
                        <Label for="button-message">Message</Label>
                        <textarea
                            id="button-message"
                            name="message"
                            placeholder="Enter your message..."
                            rows="3"
                            class="mt-1 flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
                        />
                        <InputError :message="errors.message" />
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <Label>Buttons</Label>
                            <Button
                                type="button"
                                size="sm"
                                variant="outline"
                                @click="addButton"
                            >
                                Add Button
                            </Button>
                        </div>
                        <div class="space-y-2">
                            <div
                                v-for="(button, index) in form.buttons"
                                :key="index"
                                class="flex gap-2"
                            >
                                <Input
                                    :name="`buttons[${index}][id]`"
                                    v-model="button.id"
                                    placeholder="ID"
                                    class="w-20"
                                />
                                <Input
                                    :name="`buttons[${index}][text]`"
                                    v-model="button.text"
                                    placeholder="Button Text"
                                    class="flex-1"
                                />
                                <Button
                                    v-if="form.buttons.length > 1"
                                    type="button"
                                    size="icon"
                                    variant="ghost"
                                    @click="removeButton(index)"
                                >
                                    ×
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Send Button -->
                <div class="flex justify-end pt-2">
                    <Button type="submit" :disabled="processing">
                        <Send class="mr-2 h-4 w-4" />
                        Send Message
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
