
<div>
    <div class="flex h-full flex-col items-center justify-center p-6">
        <div class="w-full max-w-md space-y-8 rounded-xl bg-white p-8 shadow-sm">
            <h2
                class="text-center text-xl font-semibold text-gray-900 dark:text-gray-100"
            >
                Connect Device: {{ $device->name }}
            </h2>

            <div class="flex flex-col items-center justify-center space-y-6">
                <!-- Connected State -->
                @if ($status['data']['loggedIn'])
                    <div class="space-y-4 text-center">
                        <div class="mx-auto w-fit rounded-full bg-emerald-100 p-4">
                            <flux:icon.check-badge class="h-12 w-12 text-emerald-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Device Connected
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $status['data']['name'] }} ({{ $status['data']['jid'] }})
                            </p>
                        </div>
                        <p class="text-sm text-gray-500">
                            This device is successfully connected to WhatsApp.
                        </p>
                    </div>

                <!-- QR Code State -->
                @elseif (!$status['data']['loggedIn'] && $status['data']['qrcode'])
                    <div class="space-y-4 text-center">
                        <div class="inline-block rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                            <img
                                src="{{ $status['data']['qrcode'] }}"
                                alt="Scan QR Code"
                                class="h-64 w-64 object-contain"
                            />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Scan QR Code
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Open WhatsApp on your phone and scan the QR code to
                                connect.
                            </p>
                        </div>
                    </div>

                <!-- Loading/Error State (Fallback) -->
                @else
                    <div class="space-y-4 text-center">
                        <div class="mx-auto w-fit rounded-full bg-amber-100 p-4">
                            <flux:icon.arrow-path class="h-12 w-12 animate-spin text-amber-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Connecting...
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                The connection is being initialized. If the QR code
                                doesn't appear, please close and try again.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="flex w-full justify-center pt-4">
                    <flux:button
                        variant="outline"
                        class="ml-4"
                        href="{{ route('cms.devices.index') }}"
                        wire:navigate
                    >
                        Back to List
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
