<?php

use App\ChangeRequest;
use App\CopyApprover;
use App\PreAssessmentApprover;
use App\RequestApprover;
use App\RolePermission;
use App\UserNotification;


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

function getUnreadDueDateAlerts()
{
    $readIds = UserNotification::where('user_id', auth()->id())
                ->where('type', 'due_date')
                ->whereNotNull('read_at')
                ->pluck('change_request_id')
                ->toArray();

    return getDueDateAlerts()->filter(fn($a) => !in_array($a->id, $readIds));
}

function getUnreadDraftRequests()
{
    $readIds = UserNotification::where('user_id', auth()->id())
                ->where('type', 'draft')
                ->whereNotNull('read_at')
                ->pluck('change_request_id')
                ->toArray();

    return getDraftRequest()->filter(fn($d) => !in_array($d->id, $readIds));
}

function getUnreadPendingApproval()
{
    $readIds = UserNotification::where('user_id', auth()->id())
                ->where('type', 'pending_approval')
                ->whereNotNull('read_at')
                ->pluck('change_request_id')
                ->toArray();

    return collect(getPendingApproval())->filter(
        fn($r) => !in_array($r->change_request->id, $readIds)
    );
}

function getUnreadCommentNotifications()
{
    return UserNotification::where('user_id', auth()->id())
        ->where('type', 'comment')
        ->whereNull('read_at')
        ->with('change_request')
        ->get();
}

function getUnreadPublishedNotifications()
{
    return UserNotification::with('change_request')
        ->where('user_id', auth()->id())
        ->where('type', 'published')
        ->whereNull('read_at')
        ->latest()
        ->get()
        ->filter(fn($n) => $n->change_request !== null);
}

function getUnreadSharedDocumentNotifications()
{
    return \App\ShareDocument::with(['document', 'sharedBy'])
        ->where('user_id', auth()->id())
        ->where('shared_by', '!=', auth()->id())
        ->whereNull('notified_at')
        ->latest()
        ->get()
        ->filter(fn($s) => $s->document !== null);
}

function canView($permission)
{
    if (auth()->user()->can($permission)) {
        return true;
    };

    return false;
}

function canCreate($permission)
{
    if (auth()->user()->can($permission)) {
        return true;
    };

    return false;
}

function canEdit($permission)
{
    if (auth()->user()->can($permission)) {
        return true;
    };

    return false;
}

// function canApprove($permissionKey)
// {
//     if (!auth()->check()) return false;

//     $role = auth()->user()->role ?? 'User';

//     $permission = RolePermission::where('role', $role)
//         ->where('permission_key', $permissionKey)
//         ->first();

//     return $permission && $permission->can_approve === 'on';
// }

function canDelete($permission)
{
    if (auth()->user()->can($permission)) {
        return true;
    };

    return false;
}