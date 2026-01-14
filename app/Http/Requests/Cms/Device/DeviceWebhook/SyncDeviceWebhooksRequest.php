<?php

namespace App\Http\Requests\Cms\Device\DeviceWebhook;

use Illuminate\Foundation\Http\FormRequest;

class SyncDeviceWebhooksRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'webhooks' => 'required|array',
            'webhooks.*.event' => 'required|string|max:255',
            'webhooks.*.url' => 'nullable|url|max:500',
            'webhooks.*.status' => 'nullable|boolean',
        ];
    }
}
