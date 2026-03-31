<?php

namespace App;

use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'due_date',
    ];

    protected $dates = [
        'due_date',
    ];

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && now()->startOfDay()->gt(Carbon::parse($this->due_date)->startOfDay());
    }

    public function getIsDueSoonAttribute(): bool
    {
        if (!$this->due_date || $this->is_overdue) {
            return false;
        }

        return now()->startOfDay()->diffInDays(
            Carbon::parse($this->due_date)->startOfDay()
        ) <= 3;
    }

    public function approvers()
    {
        return $this->hasMany(RequestApprover::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');
    }

    public function acknowledgement()
    {
        return $this->hasOne(Acknowledgement::class);
    }

    public function requestApprovers()
    {
        return $this->hasMany(RequestApprover::class, 'change_request_id', 'id');
    }

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
        return $this->belongsTo(DocumentType::class, 'type', 'id');
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
    public function visitors()
    {
        return $this->hasMany(PrivateDocsVisitor::class, 'change_request_id');
    }
    public function requestTypeList()
    {
        return $this->hasMany(RequestTypeList::class);
    }
    public function document_request_access()
    {
        return $this->hasMany(DocumentRequestAccess::class, 'change_request_id');
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'change_request_departments');
    }
}