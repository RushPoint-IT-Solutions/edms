<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentFolder extends Model
{
    public function document()
    {
        return $this->hasMany(Document::class,'folder_id','id');
    }
    
    public function childrenFolder()
    {
        return $this->hasMany(DocumentFolder::class,'parent_id','id');
    }
    
    public function parent()
    {
        return $this->belongsTo(DocumentFolder::class, 'parent_id', 'id');
    }
}