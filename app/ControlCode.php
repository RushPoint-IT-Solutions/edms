<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlCode extends Model
{
    protected $fillable = [
        'code', 'description', 'document_type_id', 'department_id', 'status',
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}