<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSystem extends Model
{
    use HasFactory;
    protected $table = 'chat_systems';
    protected $guarded = [];
}
