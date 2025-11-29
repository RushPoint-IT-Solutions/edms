<?php

use App\ChangeRequest;
use App\CopyApprover;
use App\PreAssessmentApprover;
use App\RequestApprover;


function getDraftRequest()
{
    $change_requests = ChangeRequest::whereNotNull('is_draft')->where('user_id', auth()->user()->id)->get();

    return $change_requests;
}