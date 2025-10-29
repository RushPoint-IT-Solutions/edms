<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class CopyRequest extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    public function approvers()
    {
        return $this->hasMany(CopyApprover::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function document_access()
    {
        return $this->hasOne(DocumentAccess::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class,'document_id','id');
    }
}
