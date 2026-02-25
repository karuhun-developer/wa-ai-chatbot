<?php

namespace Database\Seeders;

use App\Models\Setting\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Setting::first()) {
            Setting::create([
                'value' => [
                    'system_prompt' => 'You are a helpful AI assistant.',
                    'ai_provider' => 'openrouter',
                    'ai_model' => 'google/gemini-2.5-flash',
                ],
            ]);
        }
    }
}
