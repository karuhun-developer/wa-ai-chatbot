<script setup lang="ts">
import { show } from '@/actions/App/Http/Controllers/Cms/Device/CallbackLogController';
import Heading from '@/components/Heading.vue';
import ResourceTable from '@/components/ResourceTable.vue';
import { Button } from '@/components/ui/button';
import { usePermission } from '@/composables/usePermission';
import AppLayout from '@/layouts/AppLayout.vue';
import { PaginationItem, type BreadcrumbItem } from '@/types';
import { CallbackLogDataItem } from '@/types/cms/device';
import { Head, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Eye } from 'lucide-vue-next';

defineProps<{
    data: PaginationItem<CallbackLogDataItem>;
    orderBy?: string;
    order?: 'asc' | 'desc';
    search?: string;
    paginate?: number;
    resource: string;
}>();

const { hasPermission } = usePermission();

const title = 'Callback Logs';
const description =
    'Monitor incoming WuzAPI callback events and their payloads.';

const columns = [
    { label: 'Event Type', key: 'event_type', sortable: true },
    { label: 'Device', key: 'device.name', sortable: true },
    { label: 'IP Address', key: 'ip_address', sortable: true },
    { label: 'Created At', key: 'created_at', sortable: true },
    {
        label: 'Actions',
        key: 'actions',
        sortable: false,
        class: 'w-24 text-center',
    },
];

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Devices',
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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <Heading :title="title" :description="description" />
            </div>
            <ResourceTable
                :data="data"
                :columns="columns"
                :order-by="orderBy"
                :order="order"
                :search="search"
                :paginate="paginate"
            >
                <template #event_type="{ row }">
                    <span
                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                        :class="{
                            'bg-blue-50 text-blue-700 ring-blue-700/10':
                                row.event_type === 'Message',
                            'bg-red-50 text-red-700 ring-red-600/10':
                                row.event_type === 'Disconnected' ||
                                row.event_type === 'LoggedOut',
                            'bg-gray-50 text-gray-600 ring-gray-500/10':
                                row.event_type !== 'Message' &&
                                row.event_type !== 'Disconnected' &&
                                row.event_type !== 'LoggedOut',
                        }"
                    >
                        {{ row.event_type }}
                    </span>
                </template>
                <template #device.name="{ row }">
                    {{ row?.device_name }}
                </template>
                <template #created_at="{ row }">
                    {{ dayjs(row.created_at).format('DD MMM YYYY HH:mm:ss') }}
                </template>
                <template #actions="{ row }">
                    <div class="flex items-center justify-center gap-2">
                        <Link
                            :href="show(row.id).url"
                            v-if="hasPermission('show' + resource)"
                        >
                            <Button variant="ghost" size="icon">
                                <Eye class="h-4 w-4" />
                            </Button>
                        </Link>
                    </div>
                </template>
            </ResourceTable>
        </div>
    </AppLayout>
</template>
