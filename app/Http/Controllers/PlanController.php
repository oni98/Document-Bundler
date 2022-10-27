<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Package;
use App\Models\Enrol;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$package_id)
    {
        $this->validate($request,[
         'name'=>'required|string',
        ]);
        Plan::insert(['name'=>$request->name,'package_id'=>$package_id]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show( $package)
    {
        $package = Package::where('id',$package)->first();
        $plan = Plan::where('package_id',$package->id)->get();
        return view('backend.pages.plan.index',['plan'=>$plan,'package'=>$package]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plan = Plan::with(['package'])->where('id',$id)->first();
        return view('backend.pages.plan.edit',['plan'=>$plan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $this->validate($request,[
         'name'=>'required|string',
        ]);
        Plan::where('id',$plan->id)->update(['name'=>$request->name]);
        return redirect()->route('plan.show',[$plan->package_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function choosePlan()
    {
        $package = Package::with('plan')->get();
        return view('backend.pages.plan.choosePlan',['package'=>$package]);
    }

    public function enrolPackage($id)
    {
        $user = auth()->user()->id;
        $package_id = $id;
        Enrol::create(['user_id'=>$user,"package_id"=>$package_id]);
        return redirect()->route('bundle.index');
    }
    public function destroy($id)
    {
        $plan = Plan::where('id',$id)->delete();

        return redirect()->back();
    }
}
