<?php

namespace App\Models\Wuz;

use Illuminate\Database\Eloquent\Model;

class DeviceWebhook extends Model
{
    protected $fillable = [
        'device_id',
        'event',
        'url',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}

