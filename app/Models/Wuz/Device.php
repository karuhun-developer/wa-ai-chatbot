<?php

namespace App\Models\Wuz;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'name',
        'token',
        'connected',
        'jid',
    ];

    protected $casts = [
        'connected' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function webhooks()
    {
        return $this->hasMany(DeviceWebhook::class);
    }
}

