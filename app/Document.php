<?php

namespace App;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }
    public function copy_requests()
    {
        return $this->hasMany(CopyRequest::class);
    }
    public function change_requests()
    {
        return $this->hasMany(ChangeRequest::class);
    }
    public function revisions()
    {
        return $this->hasMany(Obsolete::class);
    }
    public function processOwner()
    {
        return $this->belongsTo(User::class,'process_owner');
    }
    public function memo_document()
    {
        return $this->hasMany(MemorandumDocument::class);
    }
}
