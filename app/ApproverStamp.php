<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApproverStamp extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
