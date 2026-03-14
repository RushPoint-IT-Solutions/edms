<?php

namespace App\Http\Controllers;

use App\Department;
use App\Document;
use App\Office;
use App\RequestApprover;
use App\DocumentRequestAccess;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function parseDateFromSearch(?string $raw): array
    {
        $raw = $raw ?? '';
        $result = [];

        if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $raw, $m)) {
            $result['exact'] = $m[1];
            return $result;
        }

        if (preg_match('/\b(\d{2}\/\d{2}\/\d{4})\b/', $raw, $m)) {
            $result['exact'] = date('Y-m-d', strtotime($m[1]));
            return $result;
        }

        $monthNames = 'jan(?:uary)?|feb(?:ruary)?|mar(?:ch)?|apr(?:il)?|may|jun(?:e)?|jul(?:y)?|aug(?:ust)?|sep(?:tember)?|oct(?:ober)?|nov(?:ember)?|dec(?:ember)?';

        if (preg_match('/\b(' . $monthNames . ')\w*\s+(\d{4})\b/i', $raw, $m)) {
            $result['month'] = date('m', strtotime('01 ' . $m[1] . ' 2000'));
            $result['year']  = $m[2];
            return $result;
        }

        if (preg_match('/\b(\d{4})\s+(' . $monthNames . ')\w*\b/i', $raw, $m)) {
            $result['year']  = $m[1];
            $result['month'] = date('m', strtotime('01 ' . $m[2] . ' 2000'));
            return $result;
        }

        if (preg_match('/^(' . $monthNames . ')\w*$/i', trim($raw), $m)) {
            $result['month'] = date('m', strtotime('01 ' . $m[1] . ' 2000'));
            return $result;
        }

        if (preg_match('/^\s*(\d{4})\s*$/', $raw, $m)) {
            $result['year'] = $m[1];
            return $result;
        }

        return $result;
    }

    private function applyDateFilter($query, array $dateParts, string $column = 'created_at'): void
    {
        if (!empty($dateParts['exact'])) {
            $query->orWhereDate($column, $dateParts['exact']);
        } elseif (!empty($dateParts['month']) && !empty($dateParts['year'])) {
            $query->orWhere(function ($q) use ($dateParts, $column) {
                $q->whereMonth($column, $dateParts['month'])
                  ->whereYear($column, $dateParts['year']);
            });
        } elseif (!empty($dateParts['month'])) {
            $query->orWhereMonth($column, $dateParts['month']);
        } elseif (!empty($dateParts['year'])) {
            $query->orWhereYear($column, $dateParts['year']);
        }
    }

    public function index(Request $request)
    {
        $today = now()->startOfDay();

        $departments = Department::where('status', 'active')
            ->orderBy('code')
            ->get();

        $offices = Office::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $documentQuery = Document::with('attachments', 'department', 'visitor')->where('public', 1);

        $docRaw       = $request->get('public_search', '');
        $docDateParts = $this->parseDateFromSearch($docRaw);

        if ($docRaw) {
            $documentQuery->where(function ($q) use ($docRaw, $docDateParts) {
                $q->where('title', 'like', '%' . $docRaw . '%')
                  ->orWhere('control_code', 'like', '%' . $docRaw . '%')
                  ->orWhereHas('department', function ($dq) use ($docRaw) {
                        $dq->where('code', 'like', '%' . $docRaw . '%')
                           ->orWhere('name', 'like', '%' . $docRaw . '%');
                    });

                $this->applyDateFilter($q, $docDateParts);
            });
        }

        $documents = $documentQuery->orderBy('created_at', 'desc')->get();

        $privateRaw       = $request->get('private_search', '');
        $privateDateParts = $this->parseDateFromSearch($privateRaw);

        $privateQuery = Document::with('attachments', 'department', 'visitor', 'owner', 'document_request_access')
            ->where('public', null);

        if ($privateRaw) {
            $privateQuery->where(function ($q) use ($privateRaw, $privateDateParts) {
                $q->where('title', 'like', '%' . $privateRaw . '%')
                  ->orWhere('file', 'like', '%' . $privateRaw . '%')
                  ->orWhereHas('department', function ($dq) use ($privateRaw) {
                        $dq->where('code', 'like', '%' . $privateRaw . '%')
                           ->orWhere('name', 'like', '%' . $privateRaw . '%');
                    });
                $this->applyDateFilter($q, $privateDateParts);
            });
        }

        $private_documents = $privateQuery
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($document) use ($today) {

                $approvedAccess = $document->document_request_access
                    ->where('requestor_id', auth()->id())
                    ->where('status', 1)
                    ->first();

                $document->has_valid_access = false;
                $document->access_expiry    = null;

                if ($approvedAccess) {
                    if (!is_null($approvedAccess->access_until)) {
                        $expiry = \Carbon\Carbon::parse($approvedAccess->access_until)->startOfDay();
                        $document->has_valid_access = $today->lte($expiry);
                        $document->access_expiry    = $approvedAccess->access_until;
                    }
                    elseif (!is_null($approvedAccess->request_date)) {
                        $expiry = \Carbon\Carbon::parse($approvedAccess->request_date)->startOfDay();
                        $document->has_valid_access = $today->lte($expiry);
                        $document->access_expiry    = $approvedAccess->request_date;
                    }
                    else {
                        $document->has_valid_access = true;
                        $document->access_expiry    = null;
                    }
                }

                $document->has_pending_request = $document->document_request_access
                    ->where('requestor_id', auth()->id())
                    ->where('status', 0)
                    ->isNotEmpty();

                return $document;
            });

        $pending_query = RequestApprover::where("user_id", auth()->id())->where("status", "Pending");

        $pendingRaw       = $request->get('pending_search', '');
        $pendingDateParts = $this->parseDateFromSearch($pendingRaw);

        if ($pendingRaw) {
            $pending_query->where(function ($q) use ($pendingRaw, $pendingDateParts) {
                $q->where('title', 'like', '%' . $pendingRaw . '%')
                  ->orWhere('file', 'like', '%' . $pendingRaw . '%')
                  ->orWhereHas('department', function ($dq) use ($pendingRaw) {
                        $dq->where('code', 'like', '%' . $pendingRaw . '%')
                           ->orWhere('name', 'like', '%' . $pendingRaw . '%');
                    });

                $this->applyDateFilter($q, $pendingDateParts);
            });
        }

        $pending_cards = $pending_query
            ->with(['change_request', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'pending_page');

        return view('monitoring', [
            'documents'         => $documents,
            'private_documents' => $private_documents,
            'pending_cards'     => $pending_cards,
            'departments'       => $departments,
            'offices'           => $offices,
        ]);
    }
}