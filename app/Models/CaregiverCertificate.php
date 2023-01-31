<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaregiverCertificate extends Model
{
    use HasFactory;

    protected $table = 'caregiver_certificates';
    protected $guarded = [];
}
