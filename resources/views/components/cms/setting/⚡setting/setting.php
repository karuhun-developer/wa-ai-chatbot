<?php

use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

new class extends Component
{
    public $system_prompt;

    public function mount()
    {
        $this->getSetting();
    }

    // Set settings
    public function getSetting()
    {
        $this->system_prompt = getSetting('system_prompt');
    }

    // Update settings
    public function save()
    {
        Setting::first()->update([
            'value' => [
                'system_prompt' => $this->system_prompt,
            ],
        ]);

        Cache::forget('global:settings');

        $this->getSetting();

        // Toast message
        $this->dispatch('toast',
            type: 'success',
            message: 'Settings updated successfully!',
        );
    }
};
