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
function getPendingApproval()
{
    $pendingApproval = RequestApprover::with('change_request')   
                                        ->where('user_id', auth()->id())
                                        ->where('status','Pending')
                                        ->get();

    return $pendingApproval;
}

function getDueDateAlerts()
{
    return ChangeRequest::where('user_id', auth()->id())
        ->whereNotNull('due_date')
        ->where('status', '!=', 'Approved')
        ->whereNull('is_draft')
        ->where(function ($q) {
            $q->whereDate('due_date', '<', today())
              ->orWhereDate('due_date', '<=', today()->addDays(3));
        })
        ->orderBy('due_date', 'asc')
        ->get();
}