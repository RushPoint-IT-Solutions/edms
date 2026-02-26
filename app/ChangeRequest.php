<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model  implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    public function approvers()
    {
        return $this->hasMany(RequestApprover::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,  'user_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }
    // public function company()
    // {
    //     return $this->belongsTo(Company::class);
    // }
    public function acknowledgement()
    {
        return $this->hasOne(Acknowledgement::class);
    }
    public function requestApprovers()
    {
        return $this->hasMany(RequestApprover::class, 'change_request_id', 'id');
    }
    // public function preAssessment()
    // {
    //     return $this->belongsTo(PreAssessment::class);
    // }
    public function supporting_documents()
    {
        return $this->hasMany(SupportingDocument::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class,'type','id');
    }
}
