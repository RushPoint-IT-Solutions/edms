<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentTypeList extends Model
{
    public function document_type() 
    {
        return $this->belongsTo(DocumentType::class,'type');
    }
}
