<div>
    <div class="flex items-center justify-between mb-4">
        @can('create' . $this->modelInstance)
            <flux:button
                variant="primary"
                icon="plus"
                @click="
                    $flux.modal('defaultModal').show();
                    $wire.dispatch('set-action');
                "
            >
                Create
            </flux:button>
        @endcan
    </div>
    <div class="flex items-center justify-between mt-5 mb-4 gap-4">
        <div class="flex items-center gap-2">
            <p class="text-sm text-gray-600">Show</p>
            <flux:select size="sm" wire:model.live.debounce="paginate" placeholder="Per Page">
                <option value="10">10 Per Page</option>
                <option value="25">25 Per Page</option>
                <option value="50">50 Per Page</option>
                <option value="100">100 Per Page</option>
            </flux:select>
        </div>

        <div class="flex items-center gap-2">
            <flux:input.group>
                <flux:input
                    size="sm"
                    icon="magnifying-glass"
                    type="text"
                    placeholder="Search ...."
                    wire:model.live.debounce="search"
                    class="max-w-xs"
                />
            </flux:input.group>
        </div>
    </div>

    @forelse($data as $d)
        <flux:card class="relative overflow-hidden transition-all hover:shadow-md">
            <!-- Status Indicator Bar -->
            <div class="absolute top-0 bottom-0 left-0 w-1.5 {{ $d->connected ? 'bg-emerald-500' : 'bg-red-500' }}">
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center justify-between p-4 sm:p-6 sm:pl-8 gap-4 lg:gap-0">
                <!-- Device Info -->
                <div class="flex flex-col gap-1">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $d->name }}
                    </h3>
                    <span class="text-sm font-medium {{ $d->connected ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ $d->connected ? 'Connected' : 'Disconnected' }}
                    </span>
                    <span class="text-xs text-muted-foreground">
                        {{ $d->device_id }}
                    </span>
                </div>

                <!-- Actions & Status -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mt-4 lg:mt-0 w-full lg:w-auto border-t border-gray-100 lg:border-t-0 pt-4 lg:pt-0">
                    <!-- Stats placeholder (if needed later) -->
                    <!-- <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span>939 pasien</span>
                        <span>9625 pesan</span>
                    </div> -->

                    <!-- Connection Control -->
                    @if(!$d->connected)
                        <flux:button
                            variant="outline"
                            icon="plus"
                            href="{{ route('cms.devices.connect') }}?device={{ $d->token }}"
                            wire:navigate
                        >
                            Connect
                        </flux:button>
                    @else
                        <flux:button
                            variant="outline"
                            icon="x-mark"
                            @click="$wire.dispatch('confirm', {
                                function: 'disconnect',
                                id: '{{ $d->id }}',
                            })"
                        >
                            Disconnect
                        </flux:button>
                    @endif

                    <!-- Toggles Stack -->
                    <div class="flex flex-col gap-3 w-full sm:w-auto sm:border-x sm:border-gray-200 sm:px-6 py-4 sm:py-0 border-y sm:border-y-0 border-gray-100" x-data="{
                        ai_enabled: {{ $d->ai_enabled ? 'true' : 'false' }},
                        auto_read: {{ $d->auto_read ? 'true' : 'false' }},
                        toggleAiEnabled() {
                            this.ai_enabled = ! this.ai_enabled;
                            $wire.toggleAiEnabled({{ $d->id }}, this.ai_enabled);
                        },
                        toggleAutoRead() {
                            this.auto_read = ! this.auto_read;
                            $wire.toggleAutoRead({{ $d->id }}, this.auto_read);
                        },
                    }">
                        <!-- AI Auto-Reply Toggle -->
                        <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-4">
                            <div class="flex items-center gap-2">
                                <flux:icon.chat-bubble-bottom-center-text variant="solid" class="h-4 w-4 text-gray-500" x-bind:class="{
                                    'text-gray-500': ! ai_enabled,
                                }" />
                                <span class="text-sm font-medium" x-bind:class="{
                                    'text-gray-500': ! ai_enabled
                                }">
                                    AI Auto-Reply
                                </span>
                            </div>
                            <flux:switch @change="toggleAiEnabled()" x-bind:checked="ai_enabled" />
                        </div>

                        <!-- Auto Read Toggle -->
                        <div class="flex items-center justify-between sm:justify-start w-full sm:w-auto gap-4">
                            <div class="flex items-center gap-2">
                                <flux:icon.eye variant="solid" class="h-4 w-4 text-gray-500" x-bind:class="{
                                    'text-gray-500': ! auto_read,
                                }" />
                                <span class="text-sm font-medium" x-bind:class="{
                                    'text-gray-500': ! auto_read
                                }">
                                    Auto Read
                                </span>
                            </div>
                            <flux:switch @change="toggleAutoRead()" x-bind:checked="auto_read" />
                        </div>
                    </div>

                    <!-- Actions Button -->
                    <div class="flex flex-wrap items-center justify-start sm:justify-end gap-2 w-full sm:w-auto mt-2 sm:mt-0 pt-2 sm:pt-0">
                        @if ($d->connected)
                            <flux:button
                                variant="outline"
                                size="sm"
                                icon="chat-bubble-left"
                                href="{{ route('cms.devices.test') }}?device={{ $d->token }}"
                                wire:navigate>
                                Test Message
                            </flux:button>
                            <flux:button
                                variant="outline"
                                size="sm"
                                icon="arrow-trending-up"
                                @click="
                                    $flux.modal('webhookModal').show();
                                    $wire.dispatch('set-action', {
                                        id: '{{ $d->id }}',
                                    });
                                ">
                                Webhook
                            </flux:button>
                        @endif
                        <flux:button
                            variant="outline"
                            size="sm"
                            icon="pencil"
                            @click="
                                $flux.modal('defaultModal').show();
                                $wire.dispatch('set-action', {
                                    id: '{{ $d->id }}',
                                });
                            ">
                            Edit
                        </flux:button>
                        <flux:button
                            variant="outline"
                            size="sm"
                            icon="shield-exclamation"
                            @click="
                                $flux.modal('proxyModal').show();
                                $wire.dispatch('set-action', {
                                    id: '{{ $d->id }}',
                                });
                            ">
                            Set Proxy
                        </flux:button>
                        <flux:button
                            variant="outline"
                            size="sm"
                            icon="trash"
                            @click="$wire.dispatch('confirm', {
                                function: 'delete',
                                id: '{{ $d->id }}',
                            })">
                            Delete
                        </flux:button>
                    </div>
                </div>
            </div>
        </flux:card>
    @empty
        <div class="text-center py-10">
            <p class="text-gray-500">No data found.</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $data->links() }}
    </div>

    <livewire:cms.devices.create-update lazy />
</div>
