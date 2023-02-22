<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovidDocument extends Model
{
    use HasFactory;

    protected $table = 'covid_documents';
    protected $guarded = [];
}
