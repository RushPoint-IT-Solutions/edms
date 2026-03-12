<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrivateDocsVisitor extends Model
{
    protected $fillable = [
        'change_request_id', 
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function changeRequest()
    {
        return $this->belongsTo(ChangeRequest::class);
    }
}