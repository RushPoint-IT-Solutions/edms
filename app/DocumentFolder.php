<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentFolder extends Model
{
    public function document()
    {
        return $this->hasMany(Document::class,'folder_id','id');
    }
}
