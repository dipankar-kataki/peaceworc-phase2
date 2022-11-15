<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizeOfficer extends Model
{
    use HasFactory;

    protected $table = 'authorize_officers';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'agency_id', 'id');
    }
}
