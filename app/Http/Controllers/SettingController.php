<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Package;
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function checkSettingAvailable($prams, $data)
    {
        $co = Setting::where(['user_id'=>$prams['user_id'],'name'=>$prams['setting_name']]);
        if($co->count() > 0)
        {
            Setting::where(['user_id'=>$prams['user_id'],'name'=>$prams['setting_name']])->update($data);
        }else{
            $data['user_id'] = $prams['user_id'];
            $data['name'] = $prams['setting_name'];
            Setting::create($data);
        }
        return true;
    }

    public function paymentSettingPage()
    {
        $data['PAYPAL_CLIENT_ID'] = Setting::where('name','PAYPAL_CLIENT_ID')->first();
        $data['PAYPAL_CLIENT_SECRET'] = Setting::where('name','PAYPAL_CLIENT_SECRET')->first();
        $data['STRIPE_PUBLISHABLE_KEY'] = Setting::where('name','STRIPE_PUBLISHABLE_KEY')->first();
        $data['STRIPE_SECRET_KEY'] = Setting::where('name','STRIPE_SECRET_KEY')->first();

        return view('backend.pages.settings.payment.index',$data);
    }

    public function paymentSettingUpdate(Request $request)
    {
        $data['type'] =$request->type;
        if($request->type == "paypal")
        {
            $prams['user_id'] = Auth::user()->id;
            $prams['setting_name'] = "PAYPAL_CLIENT_ID";
            $data['value'] = $request->PAYPAL_CLIENT_ID;
            $this->checkSettingAvailable($prams,$data);
            $prams['setting_name'] = "PAYPAL_CLIENT_SECRET";
            $data['value'] = $request->PAYPAL_CLIENT_SECRET;
            $this->checkSettingAvailable($prams,$data);
            return redirect()->back();
        }else{
            $prams['user_id'] = Auth::user()->id;
            $prams['setting_name'] = "STRIPE_PUBLISHABLE_KEY";
            $data['value'] = $request->STRIPE_PUBLISHABLE_KEY;
            $this->checkSettingAvailable($prams,$data);
            $prams['setting_name'] = "STRIPE_SECRET_KEY";
            $data['value'] = $request->STRIPE_SECRET_KEY;
            $this->checkSettingAvailable($prams,$data);
            return redirect()->back();

        }
    }
    public function planSettingPage()
    {
        $data['package'] = Package::with('plan')->paginate(10);
        return view('backend.pages.settings.plan.index',$data);
    }

    public function planSettingUpdate(Request $request)
    {
        $data['type'] =$request->type;
        if($request->type == "paypal")
        {
            $prams['user_id'] = Auth::user()->id;
            $prams['setting_name'] = "PAYPAL_CLIENT_ID";
            $data['value'] = $request->PAYPAL_CLIENT_ID;
            $this->checkSettingAvailable($prams,$data);
            $prams['setting_name'] = "PAYPAL_CLIENT_SECRET";
            $data['value'] = $request->PAYPAL_CLIENT_SECRET;
            $this->checkSettingAvailable($prams,$data);
            return redirect()->back();
        }else{
            $prams['user_id'] = Auth::user()->id;
            $prams['setting_name'] = "STRIPE_PUBLISHABLE_KEY";
            $data['value'] = $request->STRIPE_PUBLISHABLE_KEY;
            $this->checkSettingAvailable($prams,$data);
            $prams['setting_name'] = "STRIPE_SECRET_KEY";
            $data['value'] = $request->STRIPE_SECRET_KEY;
            $this->checkSettingAvailable($prams,$data);
            return redirect()->back();

        }
    }
    public function index()
    {
        $user_id = auth()->user()->id;
        $set = Setting::where(['user_id'=>$user_id])->get();
        $watermark = Setting::where(['user_id'=>$user_id,'name'=>"watermark"])->first();
        $watermark_setting = Setting::where(['user_id'=>$user_id,'name'=>"watermark_setting"])->first();
        return view("backend.pages.settings.index",['setting'=>$set,'watermark'=>$watermark,'watermark_setting'=>$watermark_setting]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $type = $request->type;
        $value = $request->values;
        $user_id = auth()->user()->id;
        if($type == "TEXT")
        {
            $setting_name ="watermark";
            $co = Setting::where(['user_id'=>$user_id,'name'=>$setting_name]);
            $value = $value;
        }else if($type == "IMG"){
            $setting_name ="watermark";
            $co = Setting::where(['user_id'=>$user_id,'name'=>$setting_name]);
            if($co->count() > 0)
            {
                $setting = $co->first();
                if($setting->type == "IMG")
                {
                    unlink(public_path('watermark/'.$setting->value));
                }
            }
            if($request->file('values')){
                if (!file_exists(public_path('watermark'))) {
                    mkdir(public_path('watermark'), 0777, true);
                }
                $file= $request->file('values');
                $input['imagename'] = time().'.'.$file->getClientOriginalExtension();

                $destinationPath = public_path('watermark');
                Image::configure(array('driver' => 'gd'));
                $img = Image::make($file->getRealPath())->rotate(45)->save($destinationPath.'/'.$input['imagename']);
                $value = $input['imagename'];
            }
        }else{

            $type = "";
            foreach(auth()->user()->roles as $role)
            {
                $type = $role->slug;
            }
            $setting_name = "watermark_setting";
            $co = Setting::where(['user_id'=>$user_id,'name'=>$setting_name]);
            $value = $value;
        }

        if($co->count() > 0){
             Setting::where(["user_id"=>$user_id,'name'=>$setting_name])->update(['type'=>$type,"name"=>$setting_name,'value'=>$value,"type"=>$type]);
        }else{

            Setting::create(['type'=>$type,"name"=>$setting_name,"type"=>$type,'value'=>$value,"user_id"=>$user_id]);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
