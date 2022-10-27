<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('frontend.homepage');
    }

    public function prices(){
        return view('frontend.prices');
    }

    public function sorry(){
        return view('frontend.sorry');
    }
}
