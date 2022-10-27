<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Plan;
class Plans extends Component
{
    public $bundle_limit,$page_limit,$default_watermark,$own_watermark,$storage_validity,$package,$price;
    protected $rules = [
        'bundle_limit.status' => 'required',
        'bundle_limit.value' => 'required',
        'page_limit.status' => 'required',
        'page_limit.value' => 'required',
        'default_watermark.status' => 'required',
        'own_watermark.status' => 'required',
        'storage_validity.status' => 'required',
        'storage_validity.*.value' => 'required',
        'price.*.value' => 'required',
    ];
    protected $messages = [
        'bundle_limit.status.required' => 'Set Limit Status.',
        'bundle_limit.value.required' => 'Write Limit Value.',
        'page_limit.status.required' => 'Select Limit Status .',
        'page_limit.value.required' => 'Write Limit Value.',
        'default_watermark.status.required' => 'Default Watermark Status.',
        'own_watermark.status.required' => 'Own Watermark Status.',
        'storage_validity.*.status.required' => 'Select Validity Status.',
        'storage_validity.*.value.required' => 'Set Storage Validity Value.',
        'price.*.value.required' => 'Set Price Value.',
    ];


    public function mount($package)
    {
        $this->package =$package;
    }
    public function render()
    {

        return view('livewire.plans',['plan'=>$this->package]);
    }
    public function store($value,$properties)
    {
        $propsAtt = explode('.',$properties);
        $name= $propsAtt[0];
        if($propsAtt[0] == "price")
        {
            if(empty($value))
            {
                $value=0;   
            }
            $this->package->price = $value;
            $this->package->save();

        }else{

            if(array_key_exists(2,$propsAtt)) {

                if($propsAtt[2] == "status")
                {
                    $data['status'] = $value;
                }else{
                    $data['values'] = $value;
                }
                Plan::updateOrCreate(
                    ['package_id' => $this->package->id, 'name' => $name],
                    $data
                );
            }

        }


    }
}
