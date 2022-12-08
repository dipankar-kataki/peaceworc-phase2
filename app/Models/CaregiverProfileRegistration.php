<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverProfileRegistration extends Model
{
    use HasFactory;

    protected $table = 'caregiver_profile_registrations';
    protected $guarded = [];
}
