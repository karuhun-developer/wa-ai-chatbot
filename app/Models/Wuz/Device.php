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
        'ai_enabled',
        'auto_read',
        'proxy_url',
        'proxy_enabled',
    ];

    protected $casts = [
        'connected' => 'boolean',
        'ai_enabled' => 'boolean',
        'auto_read' => 'boolean',
        'proxy_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function webhooks()
    {
        return $this->hasMany(DeviceWebhook::class);
    }

    public function callbackLogs()
    {
        return $this->hasMany(CallbackLog::class);
    }

    public function contacts()
    {
        return $this->hasMany(DeviceContact::class, 'device_id');
    }
}
