<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrol extends Model
{
    use HasFactory;
    protected $fillable = ['package_id',"user_id"];
    /**
     * Get the Package that owns the Enrol
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
