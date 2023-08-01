<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StripeConnectedAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stripe_connected_accounts';
    protected $guarded = [];
}
