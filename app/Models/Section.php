<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = ['name','bundle_id','user_id','sort_id','isDefault','isHiddenInList','isHiddenInGenerateIndexList','serial_alpha','isMainSection','pages'];
    public function files()
    {
       return $this->hasMany(File::class,'section_id','id')->orderBy('sort_id','asc');
    }
    public function bundle()
    {
       return $this->belongsTo(Bundle::class,'bundle_id','id');
    }
}
