<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildAbuseDocument extends Model
{
    use HasFactory;

    protected $table = 'child_abuse_documents';
    protected $guarded = [];
}
