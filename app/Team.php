<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'created_by',
    ];

    protected $dates = ['deleted_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }
}