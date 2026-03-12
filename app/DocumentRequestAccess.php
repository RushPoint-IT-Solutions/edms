<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentRequestAccess extends Model
{
    public function requestor()
    {
        return $this->belongsTo(User::class, 'requestor_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
