<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShareDocument extends Model
{
    public $timestamps = true;
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }
}
