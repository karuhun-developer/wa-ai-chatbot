<script setup lang="ts">
import { index, show } from '@/actions/App/Http/Controllers/Cms/Device/CallbackLogController';
import { index as deviceIndex } from '@/actions/App/Http/Controllers/Cms/Device/DeviceController';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { CallbackLogDataItem } from '@/types/cms/device';
import { Head, Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { ArrowLeft } from 'lucide-vue-next';

defineProps<{
    callbackLog: CallbackLogDataItem;
}>();

const title = 'Callback Details';

// Breadcrumbs
const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Devices',
        href: deviceIndex().url,
    },
    {
        title: 'Callback Logs',
        href: index().url,
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
        
        <div class="flex h-full flex-col bg-background rounded-xl overflow-hidden shadow-sm border">
            <div class="flex items-center justify-between border-b p-4">
                <div class="flex items-center gap-2">
                    <Link :href="index().url">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <h2 class="text-lg font-semibold">{{ title }}</h2>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <dl class="space-y-6">
                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-muted-foreground">Event Type</dt>
                        <dd class="sm:col-span-2">
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                :class="{
                                    'bg-blue-50 text-blue-700 ring-blue-700/10': callbackLog.event_type === 'Message',
                                    'bg-red-50 text-red-700 ring-red-600/10': callbackLog.event_type === 'Disconnected' || callbackLog.event_type === 'LoggedOut',
                                    'bg-gray-50 text-gray-600 ring-gray-500/10': callbackLog.event_type !== 'Message' && callbackLog.event_type !== 'Disconnected' && callbackLog.event_type !== 'LoggedOut'
                                }">
                                {{ callbackLog.event_type }}
                            </span>
                        </dd>
                    </div>
                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-muted-foreground">Device</dt>
                        <dd class="sm:col-span-2">{{ callbackLog.device?.name || 'Unknown' }}</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-muted-foreground">IP Address</dt>
                        <dd class="sm:col-span-2">{{ callbackLog.ip_address || '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-muted-foreground">User Agent</dt>
                        <dd class="sm:col-span-2 break-all text-sm font-mono bg-muted/50 p-2 rounded">{{ callbackLog.user_agent || '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                        <dt class="font-medium text-muted-foreground">Received At</dt>
                        <dd class="sm:col-span-2">{{ dayjs(callbackLog.created_at).format('DD MMMM YYYY HH:mm:ss') }}</dd>
                    </div>
                    
                    <div class="border-t pt-6">
                        <dt class="mb-3 font-medium text-muted-foreground">Payload</dt>
                        <dd class="rounded-lg bg-muted p-4 shadow-inner">
                            <pre class="overflow-x-auto text-xs font-mono text-foreground">{{ JSON.stringify(callbackLog.payload, null, 2) }}</pre>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </AppLayout>
</template>
