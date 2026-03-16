<?php

use App\Models\Wuz\Device;
use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.devices.test');

// Page title and breadcrumbs
render(function (View $view) {
    // Device
    $device = Device::where('token', request()->query('device'))->firstOrFail();

    // Page title and breadcrumbs
    $title = 'Test Message Device ' . $device->name;
    $description = 'Send test messages to WhatsApp numbers';
    $breadcrumbs = [
        [
            'label' => 'Devices',
            'url' => '#'
        ],
        [
            'label' => $title,
            'url' => null
        ],
    ];

    $view->with(compact('title', 'description', 'breadcrumbs', 'device'));
}); ?>

<x-layouts.app :$title>
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-4">
                <flux:button
                    href="{{ route('cms.devices.index') }}"
                    size="sm"
                    variant="primary"
                    icon="arrow-left"
                    wire:navigate
                />
                <h1 class="text-3xl font-bold">{{ $title }}</h1>
            </div>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('cms.dashboard') }}" icon="home" />
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb['url'])
                        <flux:breadcrumbs.item href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</flux:breadcrumbs.item>
                    @else
                        <flux:breadcrumbs.item>{{ $breadcrumb['label'] }}</flux:breadcrumbs.item>
                    @endif
                @endforeach
            </flux:breadcrumbs>
        </div>
        <div class="border-gray-200 mb-6">
            <flux:text>
                {{ $description }}
            </flux:text>
        </div>
        <livewire:cms.devices.test :$device />
    </div>
</x-layouts.app>
