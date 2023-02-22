<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class W4Document extends Model
{
    use HasFactory;

    protected $table='w4_documents';
    protected $guarded = [];
}
