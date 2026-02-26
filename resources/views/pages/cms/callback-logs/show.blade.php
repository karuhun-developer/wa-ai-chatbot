<?php

use App\Models\Wuz\CallbackLog;
use Illuminate\View\View;

use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('cms.callback-logs.show');

// Page title and breadcrumbs
render(function (View $view) {
    $log = CallbackLog::findOrFail(request()->query('log'));

    // Page title and breadcrumbs
    $title = 'Callback Details';
    $description = 'View the details of this WuzApi callback log entry here.';
    $breadcrumbs = [
        [
            'label' => 'Callback Logs',
            'url' => route('cms.callback-logs.index')
        ],
        [
            'label' => $title,
            'url' => null
        ]
    ];

    $view->with(compact('title', 'description', 'breadcrumbs', 'log'));
}); ?>

<x-layouts.app :$title>
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center gap-4">
                <flux:button
                    href="{{ route('cms.callback-logs.index') }}"
                    size="sm"
                    variant="primary"
                    icon="arrow-left"

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
        <livewire:cms.callback-logs.show :$log />
    </div>
</x-layouts.app>
