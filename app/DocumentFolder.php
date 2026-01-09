<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentFolder extends Model
{
    public function document()
    {
        return $this->hasMany(Document::class,'folder_id','id');
    }
    public function parentFolder()
    {
        return $this->hasMany(DocumentFolder::class,'parent_id','id');
    }
}
