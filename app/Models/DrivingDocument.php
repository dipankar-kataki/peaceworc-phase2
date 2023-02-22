<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrivingDocument extends Model
{
    use HasFactory;

    protected $table = 'driving_documents';
    protected $guarded = [];
}
