<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppDeviceToken extends Model
{
    use HasFactory;

    protected $table = 'app_device_tokens';
    protected $guarded = [];
}
