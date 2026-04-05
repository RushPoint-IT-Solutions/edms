<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChangeRequest;
use App\RequestApprover;
use App\UserNotification;
use App\ShareDocument;

class NotificationController extends Controller
{
    public function markRead(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:due_date,draft,pending_approval,comment,published,shared_document',
            'change_request_id' => 'required|integer',
        ]);

        if ($request->type === 'shared_document') {
            ShareDocument::where('user_id', auth()->id())
                ->where('document_id', $request->change_request_id)
                ->whereNull('notified_at')
                ->update(['notified_at' => now()]);

            return response()->json(['success' => true, 'is_new_read' => true]);
        }

        $userId = auth()->id();

        ShareDocument::where('user_id', $userId)
            ->whereNull('notified_at')
            ->update(['notified_at' => now()]);

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
            'type' => 'required|string|in:due_date,draft,pending_approval,comment,published,bell,all,shared_document',
        ]);

        $userId = auth()->id();
        $type   = $request->type;

        if ($type === 'shared_document') {
            ShareDocument::where('user_id', $userId)
                ->whereNull('seen_at')
                ->update(['seen_at' => now()]);

            return response()->json(['success' => true]);
        }

        switch ($type) {
            case 'all':
                $types = ['due_date', 'draft', 'pending_approval', 'comment', 'published'];
                ShareDocument::where('user_id', $userId)
                    ->whereNull('seen_at')
                    ->update(['seen_at' => now()]);
                break;
            case 'bell':
                $types = ['due_date', 'draft', 'comment', 'published'];
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

            } elseif ($t === 'published') {
                $ids = UserNotification::where('user_id', $userId)
                    ->where('type', 'published')
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