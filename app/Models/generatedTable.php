<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class generatedTable extends Model
{
    use HasFactory;
    protected $table = 'generated_bundle';
    protected $fillable = ['filename','bundle_id','auto_deleted_at'];
    public function bundle()
    {
       return $this->belongsTo(Bundle::class,'bundle_id','id');
    }
}
