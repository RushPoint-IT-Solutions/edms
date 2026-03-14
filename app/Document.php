<?php

namespace App;

use Dom\Document as DomDocument;
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
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    // public function memo_document()
    // {
    //     return $this->hasMany(MemorandumDocument::class);
    // }
    public function document_tags()
    {
        return $this->hasMany(DocumentTag::class);
    }
    public function visitor()
    {
        return $this->hasMany(DocumentVisitor::class);
    }
    public function document_request_access()
    {
        return $this->hasMany(DocumentRequestAccess::class);
    }
    public function document_type_list()
    {
        return $this->hasMany(DocumentTypeList::class);
    }
}
