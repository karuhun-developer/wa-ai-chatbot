<?php

namespace App\Http\Requests\Cms\Setting\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SaveSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => 'nullable|array',

            // AI Settings
            'value.system_prompt' => 'nullable|string',
            'value.ai_provider' => 'nullable|string',
            'value.ai_model' => 'nullable|string',
        ];
    }
}
