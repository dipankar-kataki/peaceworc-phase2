<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyInformationStatus extends Model
{
    use HasFactory;

    protected $table = 'agency_information_statuses';
    protected $guarded = [];
}
