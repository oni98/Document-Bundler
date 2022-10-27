<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;
use App\Models\Package;
use App\Models\Enrol;
use RealRashid\SweetAlert\Facades\Alert;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('users');
        }
        $enrolled_package = auth()
                          ->user()
                          ->load('enrolledPackage')->enrolledPackage;
        if(is_null($enrolled_package))
        {
            return redirect()->route('public.choosePlan');
        }
    }
    public function changePlan($user_id)
    {
        $user = User::where('id',$user_id)->first();
        $package = Package::all();
        if ($user->isAdmin()) {
            return redirect()->route('users');
        }
        $enrolled_package = $user
                          ->load('enrolledPackage')->enrolledPackage;
        return view("backend.pages.usersmanagement.change-plan",['enrolled_package'=>$enrolled_package,'user'=>$user,'package'=>$package]);
    }
    public function updatePlan(Request $request,$user_id)
    {
        $enrol = Enrol::where('user_id',$user_id)->orderBy('id','desc')->first();
        $this->validate($request,[
         'plan'=>'required',
        ]);
        $enrol->package_id = $request->plan;
        $enrol->save();
        Alert::success('Updated', 'Plan Updated Successfully');
        return redirect()->route('users');
    }

}
