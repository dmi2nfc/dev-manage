<?php

namespace App\Listeners;

use App\Events\OnSprintPayment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ExchangeRate;
use App\Currency;

class SprintPaymentListener
{
    public $minApiCryptocompare = 'https://min-api.cryptocompare.com/data/histoday?fsym={{fsym}}&tsym=USD&limit=1';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event. Get exchange rates for all currencies, for payment date
     *
     * @param  OnSprintPayment  $event
     * @return void
     */
    public function handle(OnSprintPayment $event)
    {
        $sprint = $event->sprint;

        $paymentDate = $sprint->closed_at ?
            \Carbon\Carbon::parse($sprint->closed_at)->tz('UTC')
            : \Carbon\Carbon::now()->tz('UTC');

        $currency = $sprint->currency;

        if($currency->name !== 'USD') {

            $exchangeRate = ExchangeRate::where('fsym',$currency->name)
                ->whereDate('created_at', '=', $paymentDate->toDateString())
                ->get()
                ->first();

            if(!$exchangeRate) {
                $guzzleClient = new \GuzzleHttp\Client();
                $currencies = Currency::where('name', '<>', 'USD')->get();

                foreach ($currencies as $currency) {
                    $fsym = $currency->name;
                    $apiUrl = str_replace('{{fsym}}', $fsym, $this->minApiCryptocompare).'&toTs='.$paymentDate->timestamp;

                    $responseBody = $guzzleClient
                        ->request('GET', $apiUrl)
                        ->getBody();

                    $res = json_decode($responseBody, true);

                    if($res['Data'] && $res['Data'][0]) {
                        $exchangeRate = new ExchangeRate;
                        $exchangeRate->fsym = $fsym;
                        $exchangeRate->tsym = 'USD';
                        $exchangeRate->rate = $res['Data'][0]['close'];
                        $exchangeRate->created_at = $paymentDate->format('Y-m-d H:i:s');
                        $exchangeRate->save();
                    }
                }
            }
        }
    }
}
