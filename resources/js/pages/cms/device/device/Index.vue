<script setup lang="ts">
import {
    create,
    destroy,
    disconnect,
    edit,
    show,
} from '@/actions/App/Http/Controllers/Cms/Device/DeviceController';
import { manage } from '@/actions/App/Http/Controllers/Cms/Device/DeviceWebhookController';
import { test } from '@/actions/App/Http/Controllers/Cms/Device/DeviceMessageController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { usePermission } from '@/composables/usePermission';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { PaginationItem, type BreadcrumbItem } from '@/types';
import { DeviceDataItem } from '@/types/cms/device';
import { Head, Link, router } from '@inertiajs/vue3';
import { ModalLink } from '@inertiaui/modal-vue';
import { Plus, Settings, Trash2, Webhook, MessageSquare, Bot, CheckCheck } from 'lucide-vue-next';

defineProps<{
    data: PaginationItem<DeviceDataItem>;
    orderBy?: string;
    order?: 'asc' | 'desc';
    search?: string;
    paginate?: number;
    resource: string;
}>();

const { confirm, toast } = useSwal();
const { hasPermission } = usePermission();

const title = 'Instance Management';
// const description = 'Manage devices and their connections to WhatsApp.';

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Managements',
        href: '#',
    },
    {
        title: title,
        href: '#',
    },
];

const toggleAi = (device: DeviceDataItem) => {
    router.patch(
        edit({ device: device.id }).url.replace('/edit', ''), 
        {
            name: device.name,
            ai_enabled: !device.ai_enabled,
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.fire({
                    icon: 'success',
                    title: `AI ${!device.ai_enabled ? 'enabled' : 'disabled'} for ${device.name}`,
                });
            },
            onError: () => {
                toast.fire({
                    icon: 'error',
                    title: 'Failed to update AI settings.',
                });
            }
        }
    );
};

const toggleAutoRead = (device: DeviceDataItem) => {
    router.patch(
        edit({ device: device.id }).url.replace('/edit', ''), 
        {
            name: device.name,
            auto_read: !device.auto_read,
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast.fire({
                    icon: 'success',
                    title: `Auto Read ${!device.auto_read ? 'enabled' : 'disabled'} for ${device.name}`,
                });
            },
            onError: () => {
                toast.fire({
                    icon: 'error',
                    title: 'Failed to update Auto Read setting.',
                });
            }
        }
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="title" />
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <div class="flex items-center justify-between">
                <Heading :title="title" />
                <ModalLink
                    :href="create().url"
                    slideover
                    v-if="hasPermission('create' + resource)"
                >
                    <Button class="bg-emerald-500 hover:bg-emerald-600">
                        <Plus class="mr-2 h-4 w-4" />
                        Add Instance
                    </Button>
                </ModalLink>
            </div>

            <div class="flex flex-col gap-4">
                <Card
                    v-for="device in data.data"
                    :key="device.id"
                    class="relative overflow-hidden transition-all hover:shadow-md"
                >
                    <!-- Status Indicator Bar -->
                    <div
                        class="absolute top-0 bottom-0 left-0 w-1.5"
                        :class="
                            device.connected ? 'bg-emerald-500' : 'bg-red-500'
                        "
                    ></div>

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between p-4 sm:p-6 sm:pl-8 gap-4 lg:gap-0">
                        <!-- Device Info -->
                        <div class="flex flex-col gap-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ device.name }}
                            </h3>
                            <span
                                class="text-sm font-medium"
                                :class="
                                    device.connected
                                        ? 'text-emerald-500'
                                        : 'text-red-500'
                                "
                            >
                                {{
                                    device.connected
                                        ? 'Connected'
                                        : 'Disconnected'
                                }}
                            </span>
                            <span class="text-xs text-muted-foreground">{{
                                device.device_id
                            }}</span>
                        </div>

                        <!-- Actions & Status -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mt-4 lg:mt-0 w-full lg:w-auto border-t border-gray-100 lg:border-t-0 pt-4 lg:pt-0">
                            <!-- Stats placeholder (if needed later) -->
                            <!-- <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span>939 pasien</span>
                                <span>9625 pesan</span>
                            </div> -->

                            <!-- Connection Control -->
                            <div v-if="!device.connected">
                                <Link :href="show.url(device.id)">
                                    <Button
                                        size="sm"
                                        variant="outline"
                                        class="border-emerald-500 text-emerald-500 hover:bg-emerald-50"
                                    >
                                        Connect
                                    </Button>
                                </Link>
                            </div>
                            <div v-else>
                                <Button
                                    size="sm"
                                    variant="outline"
                                    class="border-red-500 text-red-500 hover:bg-red-50"
                                    @click="
                                        confirm({
                                            title: 'Disconnect Device?',
                                            text: 'This action cannot be undone.',
                                            icon: 'warning',
                                            confirmButtonText:
                                                'Yes, disconnect it!',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.post(
                                                    disconnect({
                                                        device: device.id,
                                                    }).url,
                                                    {},
                                                    {
                                                        preserveScroll: true,
                                                        preserveState: true,
                                                        onSuccess: () => {
                                                            toast.fire({
                                                                icon: 'success',
                                                                title: 'Device disconnected successfully.',
                                                            });
                                                        },
                                                    },
                                                );
                                            }
                                        })
                                    "
                                >
                                    Disconnect
                                </Button>
                            </div>

                            <!-- Toggles Stack -->
                            <div class="flex flex-col gap-3 w-full sm:w-auto sm:border-x sm:border-gray-200 sm:px-6 py-4 sm:py-0 border-y sm:border-y-0 border-gray-100">
                                <!-- Bot Toggle -->
                                <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-4">
                                    <div class="flex items-center gap-2">
                                        <Bot class="h-4 w-4 text-gray-500" :class="{ 'text-emerald-500': device.ai_enabled }" />
                                        <span class="text-sm font-medium" :class="device.ai_enabled ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500'">AI Auto-Reply</span>
                                    </div>
                                    <button
                                        type="button"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                        :class="device.ai_enabled ? 'bg-emerald-500' : 'bg-gray-200 dark:bg-gray-700'"
                                        role="switch"
                                        :aria-checked="device.ai_enabled"
                                        @click="toggleAi(device)"
                                    >
                                        <span class="sr-only">Toggle AI Auto-Reply</span>
                                        <span
                                            aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                            :class="device.ai_enabled ? 'translate-x-5' : 'translate-x-0'"
                                        />
                                    </button>
                                </div>

                                <!-- Auto Read Toggle -->
                                <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-4">
                                    <div class="flex items-center gap-2">
                                        <CheckCheck class="h-4 w-4 text-gray-500" :class="{ 'text-emerald-500': device.auto_read }" />
                                        <span class="text-sm font-medium" :class="device.auto_read ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500'">Auto Read</span>
                                    </div>
                                    <button
                                        type="button"
                                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                        :class="device.auto_read ? 'bg-emerald-500' : 'bg-gray-200 dark:bg-gray-700'"
                                        role="switch"
                                        :aria-checked="device.auto_read"
                                        @click="toggleAutoRead(device)"
                                    >
                                        <span class="sr-only">Toggle Auto Read</span>
                                        <span
                                            aria-hidden="true"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                            :class="device.auto_read ? 'translate-x-5' : 'translate-x-0'"
                                        />
                                    </button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap items-center justify-start sm:justify-end gap-2 w-full sm:w-auto mt-2 sm:mt-0 pt-2 sm:pt-0">
                                <Link
                                    :href="test({ device: device.id }).url"
                                    v-if="hasPermission('update' + resource)"
                                >
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-9 w-9 bg-gray-100 text-gray-600 hover:bg-gray-200"
                                        title="Test Message"
                                    >
                                        <MessageSquare class="h-4 w-4" />
                                    </Button>
                                </Link>

                                <ModalLink
                                    :href="manage({ device: device.id }).url"
                                    slideover
                                    v-if="hasPermission('update' + resource)"
                                >
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-9 w-9 bg-gray-100 text-gray-600 hover:bg-gray-200"
                                        title="Manage Webhooks"
                                    >
                                        <Webhook class="h-4 w-4" />
                                    </Button>
                                </ModalLink>

                                <ModalLink
                                    :href="edit({ device: device.id }).url"
                                    slideover
                                    v-if="hasPermission('update' + resource)"
                                >
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-9 w-9 bg-gray-100 text-gray-600 hover:bg-gray-200"
                                        title="Edit Device"
                                    >
                                        <Settings class="h-4 w-4" />
                                    </Button>
                                </ModalLink>

                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-9 w-9 bg-gray-100 text-gray-600 hover:bg-gray-200"
                                    v-if="hasPermission('delete' + resource)"
                                    @click="
                                        confirm({
                                            title: 'Delete Device?',
                                            text: 'This action cannot be undone.',
                                            icon: 'warning',
                                            confirmButtonText:
                                                'Yes, delete it!',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(
                                                    destroy({
                                                        device: device.id,
                                                    }).url,
                                                    {
                                                        preserveScroll: true,
                                                        preserveState: true,
                                                        onSuccess: () => {
                                                            toast.fire({
                                                                icon: 'success',
                                                                title: 'Device deleted successfully.',
                                                            });
                                                        },
                                                    },
                                                );
                                            }
                                        })
                                    "
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </Card>
                <div
                    v-if="data.data.length === 0"
                    class="p-8 text-center text-gray-500"
                >
                    No devices found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
