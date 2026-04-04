<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRequestRevision extends Model
{
    protected $table = 'change_request_revisions';

    protected $fillable = [
        'change_request_id',
        'revision_number',
        'title',
        'description',
        'category',
        'privacy',
        'status',
        'file',
        'remarks',
        'due_date',
        'approvers_snapshot',
        'departments_snapshot',
        'document_types_snapshot',
        'submitted_by',
    ];

    protected $casts = [
        'approvers_snapshot' => 'array',
        'departments_snapshot' => 'array',
        'document_types_snapshot' => 'array',
        'due_date' => 'date',
    ];

    public function changeRequest()
    {
        return $this->belongsTo(ChangeRequest::class);
    }

    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}