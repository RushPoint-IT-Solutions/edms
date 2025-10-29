<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PreAssessment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function approvers()
    {
        return $this->hasOne(PreAssessmentApprover::class);
    }

}
