<script setup lang="ts">
import {
    create,
    destroy,
    edit,
    show,
} from '@/actions/App/Http/Controllers/Cms/Device/DeviceController';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { usePermission } from '@/composables/usePermission';
import { useSwal } from '@/composables/useSwal';
import AppLayout from '@/layouts/AppLayout.vue';
import { PaginationItem, type BreadcrumbItem } from '@/types';
import { DeviceDataItem } from '@/types/cms/device';
import { Head, Link, router } from '@inertiajs/vue3';
import { ModalLink } from '@inertiaui/modal-vue';
import { Pencil, Plus, Settings, Trash2 } from 'lucide-vue-next';

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
                        class="absolute bottom-0 left-0 top-0 w-1.5"
                        :class="
                            device.connected ? 'bg-emerald-500' : 'bg-red-500'
                        "
                    ></div>

                    <div class="flex items-center justify-between p-6 pl-8">
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
                             <span class="text-xs text-muted-foreground">{{ device.device_id }}</span>
                        </div>

                        <!-- Actions & Status -->
                        <div class="flex items-center gap-4">
                            <!-- Stats placeholder (if needed later) -->
                            <!-- <div class="flex items-center gap-4 text-sm text-gray-500">
                                <span>939 pasien</span>
                                <span>9625 pesan</span>
                            </div> -->

                            <!-- Connection Control -->
                            <div v-if="!device.connected">
                                <Link
                                    :href="show.url(device.id)"
                                >
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
                                <Badge
                                    variant="outline"
                                    class="border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100"
                                >
                                    Online
                                </Badge>
                            </div>

                            <!-- Bot Toggle Placeholder (Visual only for now if no backend support) -->
                            <!-- <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500">Bot</span>
                                <div class="h-6 w-11 rounded-full bg-emerald-500 p-1">
                                    <div class="h-4 w-4 rounded-full bg-white"></div>
                                </div>
                            </div> -->

                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                <ModalLink
                                    :href="edit({ device: device.id }).url"
                                    slideover
                                    v-if="hasPermission('update' + resource)"
                                >
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-9 w-9 bg-gray-100 text-gray-600 hover:bg-gray-200"
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
                                            confirmButtonText: 'Yes, delete it!',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                router.delete(
                                                    destroy({ device: device.id })
                                                        .url,
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
                 <div v-if="data.data.length === 0" class="text-center p-8 text-gray-500">
                    No devices found.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
