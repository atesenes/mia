<?php

namespace App\Jobs;

use App\Mail\FinishedSubscription;
use App\Models\FailedPayment;
use App\Models\PaymentLog;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionFailedRenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Tekrar Ödeme Job Çalıştı');
        //Ödeme alma işlemi 2 gün kontrol edilecek
        $failedPayments = FailedPayment::with('subscription')->where('total','<',2)->whereBetween('time',[Carbon::now()->addDays(-1)->format('Y-m-d H:') . '00',Carbon::now()->format('Y-m-d H:') . '59'])->get();

        foreach ($failedPayments as $failedPayment) {
            //Üyelik kişinin arayüzden ödemesi ile yenilendiyse tekrardan işlem yapılmasına gerek yoktur
            if ($failedPayment->subscription->finish_time < Carbon::now() && $failedPayment->subscription->is_active == 1) {
                if ($this->payment2d($failedPayment->subscription_id, false)) {
                    Subscription::where('id', $failedPayment->subscription_id)->update(['finish_time' => Carbon::now()->addMonths(1)]);
                } else {
                    //Tekrar ödeme baiarısız oldu ise sayacımızı bir arttırıyoruz.
                    $count = $failedPayment->total + 1;
                    FailedPayment::where('id', $failedPayment->id)->update(['time' => Carbon::now(), 'total' => $count]);
                    if ($count == 4) {
                        // Ödeme 2. kez başarısız oldu ise üyeliği iptal ediyoruz ve bilgilendirme yapıyoruz.
                        $subscription = Subscription::with('user')->find($failedPayment->subscription_id);
                        $subscription->is_active = 0;
                        $subscription->save();
                        Mail::to($subscription->user->email)->send(new FinishedSubscription());
                    }
                }
            }
        }
    }
    public function payment2d($subscription_id,$result)
    {
        $data['subscription_id'] = $subscription_id;
        $data['is_completed'] = $result;
        $data['description'] = 'Ödeme ile ilgili detaylar';
        $data['time'] = Carbon::now();
        PaymentLog::create($data);
        return $result;
    }
}
