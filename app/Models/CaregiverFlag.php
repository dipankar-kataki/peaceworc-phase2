<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverFlag extends Model
{
    use HasFactory;

    protected $table = 'caregiver_flags';

    protected $guarded = [];
}
