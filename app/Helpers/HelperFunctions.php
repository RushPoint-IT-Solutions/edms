<?php
use App\CopyApprover;
use App\PreAssessmentApprover;
use App\RequestApprover;


function copy_approver_count(){
    $copy_for_approvals = CopyApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $change_for_approvals = RequestApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $count = $copy_for_approvals+$change_for_approvals;
    return $count;
}

function pre_assessment_count(){
    $pre_assessment = PreAssessmentApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->where('status','Pending')->count();
    $count = $pre_assessment;
    return $count;
}

function calculateTargetDate($createdAt, $departmentHeadApproved, $typeOfDocument)

{
    $date_push = '2024-08-22';
    $createdDate = date('Y-m-d', strtotime($createdAt));

    if (in_array($typeOfDocument, ["FORM", "ANNEX", "TEMPLATE"])) {
        if ($date_push > $createdDate) {
            return date('Y-m-d', strtotime("+7 days", strtotime($createdDate)));
        } else {
            return $departmentHeadApproved != null
                ? date('Y-m-d', strtotime("+7 days", strtotime($departmentHeadApproved)))
                : date('Y-m-d');
        }
    } else {
        if ($date_push > $createdDate) {
            return date('Y-m-d', strtotime("+1 month", strtotime($createdDate)));
        } else {
            return $departmentHeadApproved != null
                ? date('Y-m-d', strtotime("+1 month", strtotime($departmentHeadApproved)))
                : date('Y-m-d');
        }
    }
    
}