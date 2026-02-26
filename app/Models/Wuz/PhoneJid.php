<?php

namespace App\Models\Wuz;

use Illuminate\Database\Eloquent\Model;

class PhoneJid extends Model
{
    protected $fillable = [
        'phone',
        'jid',
        'lid',
    ];
}
