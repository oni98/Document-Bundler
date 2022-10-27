<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get the user that owns the Plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }
}
