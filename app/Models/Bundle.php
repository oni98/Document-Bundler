<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','user_id'];
    public function section()
    {
       return $this->hasMany(Section::class,'bundle_id','id')->orderBy('sort_id','asc');
    }
    public function totalPages()
    {
        return File::where('bundle_id',$this->id)->sum('totalPage');
    }
    public function generated()
    {
       return $this->hasMany(generatedTable::class,'bundle_id','id');
    }
    public function formatdate()
    {
        return $this->created_at->format("d M Y");
    }
}
