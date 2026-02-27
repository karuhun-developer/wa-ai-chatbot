<?php

namespace App\Models\Wuz;

use Illuminate\Database\Eloquent\Model;

class DeviceContact extends Model
{
    protected $fillable = [
        'device_id',
        'push_name',
        'phone',
        'phone_jid',
        'description',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
