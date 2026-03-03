
<div>
    <!-- Create / Update Modal -->
    <flux:modal
        name="defaultModal"
        class="max-w-2xl md:min-w-2xl"
        flyout
    >
        <form class="space-y-6" wire:submit.prevent="submit">
            <div>
                <flux:heading size="lg">
                    {{ $isUpdate ? 'Update' : 'Create' }} Device
                </flux:heading>
                <flux:text class="mt-2">
                    {{ $isUpdate ? 'Update the details of the device below.' : 'Create a new device that can connect to WhatsApp' }}
                </flux:text>
            </div>

            <flux:field>
                <flux:label badge="Required">Name</flux:label>
                <flux:text>The name of the device.</flux:text>
                <flux:input wire:model="name" type="text" />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:switch wire:model="ai_enabled" label="Enable AI Auto-Reply" />
                <flux:error name="ai_enabled" />
            </flux:field>

            <flux:field>
                <flux:switch wire:model="auto_read" label="Enable Auto Read" />
                <flux:error name="auto_read" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Webhook Modal -->
    <flux:modal
        name="webhookModal"
        class="max-w-2xl md:min-w-2xl"
        flyout
    >
        <form class="space-y-6" wire:submit.prevent="saveWebhooks">
            <div>
                <flux:heading size="lg">
                    Manage Webhooks - {{ $name }}
                </flux:heading>
                <flux:text class="mt-2">
                    Configure webhook URLs for different events. Leave URL empty to disable a webhook.
                </flux:text>
            </div>

            @php
                $events = [
                    [
                        'event' => 'All',
                        'label' => 'All Events',
                        'description' => 'Triggered for all events',
                    ],
                    [
                        'event' => 'MessageSent',
                        'label' => 'Message Sent',
                        'description' => 'Triggered when a message is sent',
                    ],
                    [
                        'event' => 'MessageReceived',
                        'label' => 'Message Received',
                        'description' => 'Triggered when a message is received',
                    ],
                    [
                        'event' => 'MessageRead',
                        'label' => 'Message Read',
                        'description' => 'Triggered when a message is read',
                    ],
                    [
                        'event' => 'MessageDeleted',
                        'label' => 'Message Deleted',
                        'description' => 'Triggered when a message is deleted',
                    ],
                ];
            @endphp

            @foreach ($events as $event)
                <flux:field>
                    <flux:label>
                        {{ $event['label'] }}
                    </flux:label>
                    <flux:text>
                        {{ $event['description'] }}
                    </flux:text>
                    <flux:input wire:model="webhook.{{ $event['event'] }}.url" type="text" placeholder="https://example.com/webhook" />
                    <flux:switch wire:model="webhook.{{ $event['event'] }}.status" label="Enable Webhook" />
                    <flux:error name="name" />
                </flux:field>
            @endforeach

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal
        name="proxyModal"
        class="max-w-2xl md:min-w-2xl"
        flyout
    >
        <form class="space-y-6" wire:submit.prevent="saveProxy">
            <div>
                <flux:heading size="lg">
                    Manage Proxy - {{ $name }}
                </flux:heading>
                <flux:text class="mt-2">
                    Configure proxy settings for the device. This is useful if your server is behind a proxy or firewall.
                </flux:text>
            </div>
            <flux:field>
                <flux:label>
                    Proxy URL
                </flux:label>
                <flux:text>
                    The URL of the proxy server, including the port (e.g., socks5://proxy.example.com:1080).
                </flux:text>
                <flux:input wire:model="proxy_url" type="text" placeholder="socks5://proxy.example.com:1080" />
                <flux:error name="proxy_url" />
            </flux:field>

            <flux:field>
                <flux:switch wire:model="proxy_enabled" label="Enable Proxy" />
                <flux:error name="proxy_enabled" />
            </flux:field>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Save changes</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
