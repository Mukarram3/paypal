<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;

class PaymentController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_LIVE_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_LIVE_CLIENT_SECRET'));
        $this->gateway->setTestMode(env('PAYPAL_TEST_MODE'));
    }

    public function form()
    {
        return view('form');
    }

    public function pay(Request $request)
    {
        try {
            $response = $this->gateway->purchase([
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => route('payment.success'),
                'cancelUrl' => route('payment.cancel'),
            ])->send();

            if ($response->isRedirect()) {
                $response->redirect();
            } else {
                // not successful
                dd($response->getMessage());
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function success(Request $request)
    {
        dd($request->all());
    }

    public function cancel(Request $request)
    {
        dd($request->all());
    }
}
