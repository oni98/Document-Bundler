<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Omnipay\Omnipay;
class PaymentController extends Controller
{

    public function index($id,$price)
    {
        if($price == 0)
        {
            return redirect()->route("choosePackage",[$id]);
        }
        return view("backend.pages.plan.payment",["package_id"=>$id,'price'=>$price]);
    }

}
