<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageSiteLayout extends Model
{
    use HasFactory;

    protected $table = 'manage_layouts';
    
    protected $guarded = [];
}
