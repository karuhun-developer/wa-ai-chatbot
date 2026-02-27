<?php

namespace App\Models\Wuz;

use Illuminate\Database\Eloquent\Model;

class DeviceMessage extends Model
{
    protected $fillable = [
        'device_id',
        'device_contact_id',
        'chat_jid',
        'sender_jid',
        'message',
        'metadata',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function contact()
    {
        return $this->belongsTo(DeviceContact::class, 'device_contact_id');
    }
}
