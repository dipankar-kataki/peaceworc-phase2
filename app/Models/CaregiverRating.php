<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverRating extends Model
{
    use HasFactory;

    protected $table = 'caregiver_ratings';
    protected $guarded = [];
}
