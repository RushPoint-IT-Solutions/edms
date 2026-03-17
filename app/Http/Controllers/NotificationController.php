<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChangeRequest;
use App\RequestApprover;
use App\UserNotification;

class NotificationController extends Controller
{
    public function markRead(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:due_date,draft,pending_approval,comment',
            'change_request_id' => 'required|integer',
        ]);

        $notification = UserNotification::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'type' => $request->type,
                'change_request_id' => $request->change_request_id,
            ]
        );

        $isNewRead = false;

        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
            $isNewRead = true;
        }

        return response()->json([
            'success' => true,
            'is_new_read' => $isNewRead,
        ]);
    }

    public function markAllRead(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:due_date,draft,pending_approval,comment,bell,all',
        ]);

        $userId = auth()->id();
        $type   = $request->type;

        switch ($type) {
            case 'all':
                $types = ['due_date', 'draft', 'pending_approval', 'comment'];
                break;
            case 'bell':
                $types = ['due_date', 'draft', 'comment'];
                break;
            default:
                $types = [$type];
                break;
        }

        foreach ($types as $t) {
            if ($t === 'due_date') {
                $ids = ChangeRequest::where('user_id', $userId)
                    ->whereNotNull('due_date')
                    ->where('status', '!=', 'Approved')
                    ->whereNull('is_draft')
                    ->where(function ($q) {
                        $q->whereDate('due_date', '<', today())
                        ->orWhereDate('due_date', '<=', today()->addDays(3));
                    })
                    ->pluck('id');

            } elseif ($t === 'draft') {
                $ids = ChangeRequest::whereNotNull('is_draft')
                    ->where('user_id', $userId)
                    ->pluck('id');

            } elseif ($t === 'pending_approval') {
                $ids = RequestApprover::with('change_request')
                    ->where('user_id', $userId)
                    ->where('status', 'Pending')
                    ->get()
                    ->pluck('change_request.id');

            } elseif ($t === 'comment') {
                $ids = UserNotification::where('user_id', $userId)
                    ->where('type', 'comment')
                    ->whereNull('read_at')
                    ->pluck('change_request_id');
            }

            foreach ($ids as $id) {
                UserNotification::firstOrCreate(
                    ['user_id' => $userId, 'type' => $t, 'change_request_id' => $id]
                )->update(['read_at' => now()]);
            }
        }

        return response()->json(['success' => true]);
    }
}