<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\Document;
use App\Office;
use App\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublishDocumentController extends Controller
{

    public function publishDocument(Request $request)
    {
        $request->validate([
            'change_request_id' => 'required|exists:change_requests,id',
            'publish_type' => 'required|in:immediate,scheduled',
            'publish_date' => 'sometimes|nullable|date|after_or_equal:today',
        ]);

        if ($request->publish_type === 'scheduled' && empty($request->publish_date)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Please provide a publish date for scheduled publishing.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            $cr = ChangeRequest::findOrFail($request->change_request_id);

            if ($cr->status !== 'Approved') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Only approved documents can be published.',
                ], 422);
            }

            if ($request->publish_type === 'immediate') {
                $cr->published_at = Carbon::now();
                $cr->publish_at = null;
                $cr->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Document published successfully! It is now visible in Monitoring.',
                ]);
            } else {
                $cr->published_at = null;
                $cr->publish_at = Carbon::parse($request->publish_date)->startOfDay();
                $cr->save();

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Scheduled to publish on ' . Carbon::parse($request->publish_date)->format('M d, Y') . '.',
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error publishing document: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getOffices()
    {
        try {
            $departments = Department::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'code']);

            return response()->json($departments);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching departments: ' . $e->getMessage());
            return response()->json([]);
        }
    }
}