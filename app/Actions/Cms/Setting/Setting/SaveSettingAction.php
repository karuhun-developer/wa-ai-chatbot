<?php

namespace App\Actions\Cms\Setting\Setting;

use App\Models\Setting\Setting;
use Illuminate\Support\Facades\Cache;

class SaveSettingAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): bool
    {
        // Forget the cache for global settings to ensure that the updated settings are reflected immediately.
        Cache::forget('global:settings');

        $setting = Setting::first();

        if (! $setting) {
            $setting = Setting::create(['value' => []]);
        }

        return $setting->update([
            'value' => [
                ...(is_array($setting->value) ? $setting->value : []),
                ...($data['value'] ?? []),
            ],
        ]);
    }
}
