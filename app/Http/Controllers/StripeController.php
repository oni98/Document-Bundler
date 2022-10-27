<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Omnipay\Omnipay;
use App\Models\Setting;
class StripeController extends Controller
{

    public $gateway;
    public $completePaymentUrl;

    public function __construct()
    {
        $STRIPE_SECRET_KEY = Setting::where('name','STRIPE_SECRET_KEY')->first();

        $this->gateway = Omnipay::create('Stripe\PaymentIntents');
        $this->gateway->setApiKey(data_get($STRIPE_SECRET_KEY,'value',''));
        $this->completePaymentUrl = url("payment/stripe/success/");
    }

    public function index()
    {
        $amount =request('amount');
        $package_id =request('package_id');
        $STRIPE_PUBLISHABLE_KEY = Setting::where('name','STRIPE_PUBLISHABLE_KEY')->first();
        return view('backend.pages.plan.stripe',['amount'=>$amount,'package_id'=>$package_id,'STRIPE_PUBLISHABLE_KEY'=>$STRIPE_PUBLISHABLE_KEY]);
    }

    public function charge(Request $request,$package_id)
    {
        if($request->input('stripeToken'))
        {
            $token = $request->input('stripeToken');

            $response = $this->gateway->authorize([
                'amount' => $request->input('amount'),
                'currency' => env('STRIPE_CURRENCY'),
                'description' => 'This is a X purchase transaction.',
                'token' => $token,
                'returnUrl' => $this->completePaymentUrl,
                'confirm' => true,
            ])->send();

            if($response->isSuccessful())
            {
                $response = $this->gateway->capture([
                    'amount' => $request->input('amount'),
                    'currency' => env('STRIPE_CURRENCY'),
                    'paymentIntentReference' => $response->getPaymentIntentReference(),
                ])->send();

                $arr_payment_data = $response->getData();
                $this->store_payment([
                    'payment_id' => $arr_payment_data['id'],
                    'payer_id' => $arr_payment_data['id'],
                    'payer_email' => $request->input('email'),
                    'amount' => $arr_payment_data['amount']/100,
                    'currency' => env('STRIPE_CURRENCY'),
                    'payment_status' => $arr_payment_data['status'],
                ]);

                return redirect()->route("choosePackage",[$package_id]);
            }
            elseif($response->isRedirect())
            {
                session(['payer_email' => $request->input('email')]);
                $response->redirect();
            }
            else
            {
                return redirect()->back()->withError( $response->getMessage());
            }
        }
    }

    public function confirm(Request $request,$package_id)
    {
        $response = $this->gateway->confirm([
            'paymentIntentReference' => $request->input('payment_intent'),
            'returnUrl' => $this->completePaymentUrl,
        ])->send();

        if($response->isSuccessful())
        {
            $response = $this->gateway->capture([
                'amount' => $request->input('amount'),
                'currency' => env('STRIPE_CURRENCY'),
                'paymentIntentReference' => $request->input('payment_intent'),
            ])->send();

            $arr_payment_data = $response->getData();

            $this->store_payment([
                'payment_id' => $arr_payment_data['id'],
                'payer_email' => session('payer_email'),
                'amount' => $arr_payment_data['amount']/100,
                'payer_id' => $arr_payment_data['id'],
                'currency' => env('STRIPE_CURRENCY'),
                'payment_status' => $arr_payment_data['status'],
            ]);

            return redirect()->route("choosePackage",[$package_id]);
        }
        else
        {
            return redirect()->back()->withError( $response->getMessage());
        }
    }

    public function store_payment($arr_data = [])
    {
        $isPaymentExist = Payment::where('payment_id', $arr_data['payment_id'])->first();

        if(!$isPaymentExist)
        {
            $payment = new Payment;
            $payment->payment_id = $arr_data['payment_id'];
            $payment->payer_email = $arr_data['payer_email'];
            $payment->payer_id = $arr_data['payer_id'];
            $payment->amount = $arr_data['amount'];
            $payment->currency = env('STRIPE_CURRENCY');
            $payment->payment_status = $arr_data['payment_status'];
            $payment->save();
        }
    }
}
