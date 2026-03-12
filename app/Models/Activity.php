<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'description'
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
