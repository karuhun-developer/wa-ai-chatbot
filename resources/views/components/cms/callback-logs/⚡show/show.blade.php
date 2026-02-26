
<div>
    <div class="flex-1 overflow-y-auto p-6">
        <dl class="space-y-6">
            <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                <dt class="font-medium text-muted-foreground">Event Type</dt>
                <dd class="sm:col-span-2">
                    <flux:badge color="{{ $log->event_type->color() }}" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset">
                        {{ $log->event_type->label() }}
                    </flux:badge>
                </dd>
            </div>
            <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                <dt class="font-medium text-muted-foreground">Device</dt>
                <dd class="sm:col-span-2">{{ $log->device->name ?? '-' }}</dd>
            </div>
            <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                <dt class="font-medium text-muted-foreground">IP Address</dt>
                <dd class="sm:col-span-2">{{ $log->ip_address ?? '-' }}</dd>
            </div>
            <div class="grid grid-cols-1 gap-1 sm:grid-cols-3 sm:gap-4">
                <dt class="font-medium text-muted-foreground">Received At</dt>
                <dd class="sm:col-span-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</dd>
            </div>

            <div class="border-t pt-6">
                <dt class="mb-3 font-medium text-muted-foreground">Payload</dt>
                <dd class="rounded-lg bg-muted p-4 shadow-inner" x-data="{
                    payload: @js($log->payload),
                }">
                    <pre class="overflow-x-auto text-xs font-mono text-foreground" x-html="JSON.stringify(payload, null, 2)"></pre>
                </dd>
            </div>
        </dl>
    </div>
</div>
