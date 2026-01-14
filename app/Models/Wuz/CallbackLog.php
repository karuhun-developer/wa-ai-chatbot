<?php

namespace App\Models\Wuz;

use Illuminate\Database\Eloquent\Model;

class CallbackLog extends Model
{
    protected $fillable = [
        'device_id',
        'event_type',
        'payload',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
