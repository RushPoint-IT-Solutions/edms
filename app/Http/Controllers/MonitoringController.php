<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\Department;
use App\Document;
use App\Office;
use App\RequestApprover;
use App\DocumentRequestAccess;
use App\PrivateDocsVisitor;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
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
        if (!canView('monitoring.view')) {
            return view('pages.403-error');
        }

        $departments = Department::where('status', 'active')->orderBy('code')->get();
        $offices = Office::where('status', 'Active')->orderBy('name')->get();

        $today = now()->startOfDay();
        $privateQuery = ChangeRequest::with([
            'department', 'department.office', 'user',
            'document_request_access', 'document.attachments',
        ])
            ->whereIn('status', ['Approved', 'For Approval'])
            ->where('category', 'Private');

        $private_documents = $privateQuery->orderBy('created_at', 'desc')->get()
            ->map(function ($document) use ($today) {
                $approvedAccess = $document->document_request_access
                    ->where('requestor_id', auth()->id())
                    ->where('status', 1)
                    ->first();

                $document->has_valid_access = false;
                $document->access_expiry = null;

                if ($approvedAccess) {
                    if (!is_null($approvedAccess->access_until)) {
                        $expiry = \Carbon\Carbon::parse($approvedAccess->access_until)->startOfDay();
                        $document->has_valid_access = $today->lte($expiry);
                        $document->access_expiry = $approvedAccess->access_until;
                    } elseif (!is_null($approvedAccess->request_date)) {
                        $expiry = \Carbon\Carbon::parse($approvedAccess->request_date)->startOfDay();
                        $document->has_valid_access = $today->lte($expiry);
                        $document->access_expiry = $approvedAccess->request_date;
                    } else {
                        $document->has_valid_access = true;
                        $document->access_expiry = null;
                    }
                }

                $document->has_pending_request = $document->document_request_access
                    ->where('requestor_id', auth()->id())
                    ->where('status', 0)
                    ->isNotEmpty();

                return $document;
            });

        return view('monitoring', [
            'departments' => $departments,
            'offices' => $offices,
            'private_documents' => $private_documents,
        ]);
    }

    public function getPendingCards(Request $request)
    {
        $raw = $request->get('pending_search', '');
        $dateParts = $this->parseDateFromSearch($raw);
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 8;

        $query = RequestApprover::where('user_id', auth()->id())
            ->where('status', 'Pending');

        if ($raw) {
            $query->where(function ($q) use ($raw, $dateParts) {
                $q->where('title', 'like', '%' . $raw . '%')
                  ->orWhere('file', 'like', '%' . $raw . '%')
                  ->orWhereHas('change_request.department', function ($dq) use ($raw) {
                      $dq->where('code', 'like', '%' . $raw . '%')
                         ->orWhere('name', 'like', '%' . $raw . '%');
                  });
                $this->applyDateFilter($q, $dateParts);
            });
        }

        $paginator = $query
            ->with(['change_request.department.office', 'change_request.approvers', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $userId = auth()->id();

        $items = $paginator->map(function ($approver) use ($userId) {
            $cr = $approver->change_request;
            $myApprover = $cr->approvers->firstWhere('user_id', $userId);
            $myStatus = $myApprover ? $myApprover->status : null;

            $fileParts = explode('/', $cr->file);
            $filename  = end($fileParts);

            return [
                'id' => $cr->id,
                'file' => $cr->file,
                'filename' => $filename,
                'my_status' => $myStatus,
                'dept_code' => optional($cr->department)->code,
                'office_name' => optional(optional($cr->department)->office)->name
                              ?? optional(optional($cr->department)->office)->code,
                'created_at' => date('M d, Y', strtotime($cr->created_at)),
                'days_ago' => (new \DateTime($cr->updated_at))->diff(new \DateTime())->d,
            ];
        });

        return response()->json([
            'data' => $items,
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'first_item' => $paginator->firstItem(),
            'last_item' => $paginator->lastItem(),
        ]);
    }

    public function getPrivateDocuments(Request $request)
    {
        $today = now()->startOfDay();
        $raw = $request->get('private_search', '');
        $dateParts = $this->parseDateFromSearch($raw);

        $query = ChangeRequest::with([
            'department', 'department.office', 'user',
            'document_request_access',
        ])
            ->whereIn('status', ['Approved', 'For Approval'])
            ->where('category', 'Private');

        if ($raw) {
            $query->where(function ($q) use ($raw, $dateParts) {
                $q->where('title', 'like', '%' . $raw . '%')
                  ->orWhere('control_code', 'like', '%' . $raw . '%')
                  ->orWhereHas('department', function ($dq) use ($raw) {
                      $dq->where('code', 'like', '%' . $raw . '%')
                         ->orWhere('name', 'like', '%' . $raw . '%');
                  });
                $this->applyDateFilter($q, $dateParts);
            });
        }

        $userId = auth()->id();

        $documents = $query->orderBy('created_at', 'desc')->get()
            ->map(function ($document) use ($today, $userId) {
                $approvedAccess = $document->document_request_access
                    ->where('requestor_id', $userId)
                    ->where('status', 1)
                    ->first();

                $hasValidAccess = false;
                $accessExpiry   = null;

                if ($approvedAccess) {
                    if (!is_null($approvedAccess->access_until)) {
                        $expiry = \Carbon\Carbon::parse($approvedAccess->access_until)->startOfDay();
                        $hasValidAccess = $today->lte($expiry);
                        $accessExpiry = $approvedAccess->access_until;
                    } elseif (!is_null($approvedAccess->request_date)) {
                        $expiry = \Carbon\Carbon::parse($approvedAccess->request_date)->startOfDay();
                        $hasValidAccess = $today->lte($expiry);
                        $accessExpiry = $approvedAccess->request_date;
                    } else {
                        $hasValidAccess = true;
                        $accessExpiry = null;
                    }
                }

                $hasPendingRequest = $document->document_request_access
                    ->where('requestor_id', $userId)
                    ->where('status', 0)
                    ->isNotEmpty();

                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'control_code' => $document->control_code,
                    'owner_name' => optional($document->user)->name,
                    'created_at' => date('M d, Y', strtotime($document->created_at)),
                    'file' => $document->file,
                    'has_valid_access' => $hasValidAccess,
                    'has_pending_request' => $hasPendingRequest,
                    'access_expiry' => $accessExpiry
                        ? \Carbon\Carbon::parse($accessExpiry)->format('M d, Y')
                        : null,
                    'visitor_count'       => optional($document->visitors)->count() ?? 0,
                ];
            });

        return response()->json(['data' => $documents]);
    }

    public function getPublicDocuments(Request $request)
    {
        $userDeptId = auth()->user()->department_id;
        $raw = $request->get('public_search', '');
        $dateParts  = $this->parseDateFromSearch($raw);

        $query = ChangeRequest::with(['department', 'department.office', 'user', 'visitors'])
            ->where('category', 'Public')
            ->where(function ($q) {
                $q->whereNotNull('published_at')
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('publish_at')
                         ->where('publish_at', '<=', now());
                  });
            })
            ->where(function ($q) use ($userDeptId) {
                $q->whereNull('publish_office_ids')
                  ->orWhere('publish_office_ids', '[]')
                  ->orWhere('publish_office_ids', '')
                  ->orWhereRaw('JSON_CONTAINS(publish_office_ids, ?)', [json_encode((string) $userDeptId)])
                  ->orWhereRaw('JSON_CONTAINS(publish_office_ids, ?)', [json_encode((int) $userDeptId)]);
            });

        if ($raw) {
            $query->where(function ($q) use ($raw, $dateParts) {
                $q->where('title', 'like', '%' . $raw . '%')
                  ->orWhere('control_code', 'like', '%' . $raw . '%')
                  ->orWhereHas('department', function ($dq) use ($raw) {
                      $dq->where('code', 'like', '%' . $raw . '%')
                         ->orWhere('name', 'like', '%' . $raw . '%');
                  });
                $this->applyDateFilter($q, $dateParts);
            });
        }

        $documents = $query->orderBy('published_at', 'desc')->get()->map(function ($document) {
            return [
                'id' => $document->id,
                'title' => $document->title,
                'control_code' => $document->control_code
                    ?? 'DOC-' . str_pad($document->id, 3, '0', STR_PAD_LEFT),
                'file' => $document->file,
                'file_url' => $document->file ? url($document->file) : null,
                'published_at' => date('M d, Y', strtotime($document->published_at ?? $document->created_at)),
                'visitor_count' => $document->visitors->count(),
                'dept_code' => optional($document->department)->code,
                'office_name' => optional(optional($document->department)->office)->name
                                     ?? optional(optional($document->department)->office)->code,
                'publish_office_ids' => $document->publish_office_ids,
            ];
        });

        return response()->json(['data' => $documents]);
    }

    public function getChangeRequests(Request $request)
    {
        $raw = $request->get('tracking_search', '');
        $dateParts = $this->parseDateFromSearch($raw);

        $query = ChangeRequest::with([
            'approvers.user.department',
            'department',
            'office',
            'user',
            'supporting_documents',
        ])
            ->where('user_id', auth()->id());

        if ($raw) {
            $query->where(function ($q) use ($raw, $dateParts) {
                $q->where('title', 'like', '%' . $raw . '%')
                  ->orWhere('status', 'like', '%' . $raw . '%')
                  ->orWhere('control_code', 'like', '%' . $raw . '%')
                  ->orWhere('description', 'like', '%' . $raw . '%')
                  ->orWhereHas('department', function ($dq) use ($raw) {
                      $dq->where('code', 'like', '%' . $raw . '%')
                         ->orWhere('name', 'like', '%' . $raw . '%');
                  });
                $this->applyDateFilter($q, $dateParts);
            });
        }

        $changeRequests = $query->orderBy('created_at', 'desc')->get()->map(function ($cr) {
            $pageCount = 0;
            if ($cr->file && file_exists(public_path($cr->file))) {
                try {
                    $pdf = new \setasign\Fpdi\Fpdi();
                    $pageCount = $pdf->setSourceFile(public_path($cr->file));
                } catch (\Exception $e) {
                    $pageCount = 0;
                }
            }

            return [
                'id' => $cr->id,
                'title' => $cr->title,
                'status' => $cr->status,
                'category' => $cr->category,
                'doc_code' => 'DOC-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT),
                'control_code' => $cr->control_code,
                'description' => $cr->description,
                'file_url' => $cr->file ? url($cr->file) : null,
                'filename' => $cr->file ? basename($cr->file) : null,
                'page_count' => $pageCount,
                'supporting_doc_count' => $cr->supporting_documents->count(),
                'office_name' => optional($cr->department)->name,
                'dept_code' => optional($cr->department)->code,
                'submitter' => optional($cr->user)->name,
                'created_at' => date('M d, Y', strtotime($cr->created_at)),
                'updated_at' => date('M d, Y h:i A', strtotime($cr->updated_at)),
                'approvers' => $cr->approvers->map(function ($a) {
                    return [
                        'level' => $a->level,
                        'name' => optional($a->user)->name,
                        'office' => optional(optional($a->user)->department)->name ?? '—',
                        'start_date' => $a->start_date
                            ? date('M d Y h:i A', strtotime($a->start_date))
                            : '—',
                        'transaction_date' => $a->status === 'Approved'
                            ? date('M d Y h:i A', strtotime($a->updated_at))
                            : '',
                        'remarks' => $a->remarks ?? '',
                        'status' => $a->status,
                    ];
                }),
            ];
        });

        return response()->json(['data' => $changeRequests]);
    }

    public function privateUserView(Request $request)
    {
        PrivateDocsVisitor::create([
            'change_request_id' => $request->change_request_id,
            'user_id' => auth()->id(),
        ]);
    }
}