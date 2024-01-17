<?php

namespace App\Jobs;

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

class SubscriptionRenewalJob implements ShouldQueue
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
        Log::info('Ödeme Kontrol Job Çalıştı');
        //Bugün bu saatte üyelikleri biten aktif kullanıcıları listeliyoruz.
        $finishSubscriptions = Subscription::where('is_active',1)->whereBetween('finish_time',[Carbon::now()->format('Y-m-d H:') . '00',Carbon::now()->format('Y-m-d H:') . '59'])->get();
        foreach ($finishSubscriptions as $subscription)
        {
            //Ödeme işlemine gönderiyoruz
            //Ödeme entegrasyonu olmadığı için sonucunu result parametresiyle bildiriyoruz
            if ($this->payment2d($subscription->id,false))
            {
                //Ödeme başarılıysa üyeliği 1 ay uzatıyoruz.
                Subscription::where('id',$subscription->id)->update(['finish_time'=>Carbon::now()->addMonths(1)]);
            }
            else
            {
                //Ödeme başarısız ise tamamlanmayan ödemeler tablosuna kaydediyoruz.
                FailedPayment::create(['subscription_id'=>$subscription->id,'time'=>Carbon::now(),'total'=>0]);
            }
        }
    }
    public function payment2d($subscription_id,$result)
    {
        //Ödeme kaydı tutuyoruz
        $data['subscription_id'] = $subscription_id;
        $data['is_completed'] = $result;
        $data['description'] = 'Ödeme ile ilgili detaylar';
        $data['time'] = Carbon::now();
        PaymentLog::create($data);
        return $result;
    }
}
