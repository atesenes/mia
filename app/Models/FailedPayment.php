<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'total',
        'time',
    ];
    public function subscription()
    {
        return $this->hasOne(Subscription::class,'id','subscription_id');
    }
}
