<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentLog extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'subscription_id',
        'description',
        'time',
        'is_completed',
    ];
}
