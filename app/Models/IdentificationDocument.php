<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentificationDocument extends Model
{
    use HasFactory;

    protected $table = 'identification_documents';
    protected $guarded = [];
}
