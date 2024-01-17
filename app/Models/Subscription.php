<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'start_time',
        'finish_time',
        'is_active',
    ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
