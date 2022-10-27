<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['name','price'];
    protected $append = ['plan'];
    public function DatasByProps($name)
    {
        $plan = Plan::where(['package_id'=>$this->id,'name'=>$name])->first();
        return $plan;
    }
    public function plan()
    {
        return $this->hasMany(Plan::class, 'package_id', 'id');
    }
}
