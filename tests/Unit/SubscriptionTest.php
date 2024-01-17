<?php

namespace Tests\Unit;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $user = User::create(['name'=>'Ali','email'=>'ali@admin.com','password'=>Hash::make('secret')]);
        $subscription = Subscription::create(['user_id'=>$user->id,'start_time'=>Carbon::now()->addMonths(-1),'finish_time'=>Carbon::now()]);

        Artisan::call('schedule:run');

        $this->assertTrue(true);
    }
}
