<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Setting;
use Omnipay\Omnipay;
class PaypalController extends Controller
{
    private $gateway;
    public function __construct(Request $request)
    {
        $PAYPAL_CLIENT_ID = Setting::where('name','PAYPAL_CLIENT_ID')->first();
        $PAYPAL_CLIENT_SECRET = Setting::where('name','PAYPAL_CLIENT_SECRET')->first();

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(data_get($PAYPAL_CLIENT_ID,'value',''));
        $this->gateway->setSecret(data_get($PAYPAL_CLIENT_SECRET,'value',''));
        $this->gateway->setTestMode(true);

    }
    public function payment(Request $request)
    {

        $response = $this->gateway->purchase(array(
            'amount' => $request->amount,
            'currency' => env('PAYPAL_CURRENCY'),
            'returnUrl' => route("payment.success",[$request->package_id]),
            'cancelUrl' => route("payment.cancel")
        ))->send();
        if ($response->isRedirect()) {
            $response->redirect();
        }
        else{
            return $response->getMessage();
        }

    }
    public function success(Request $request,$package_id)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                $arr = $response->getData();

                $payment = new Payment();
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = env('PAYPAL_CURRENCY');
                $payment->payment_status = $arr['state'];

                $payment->save();
                return redirect()->route("choosePackage",[$package_id]);


            }
            else{
                return $response->getMessage();
            }
        }
        else{
            return 'Payment declined!!';
        }
    }

    public function error()
    {
        return 'User declined the payment!';
    }
}
