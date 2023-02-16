<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareType extends Model
{
    use HasFactory;

    protected $table = 'care_types';
    protected $guarded = [];
}
