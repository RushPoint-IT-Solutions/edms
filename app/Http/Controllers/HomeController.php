<?php

namespace App\Http\Controllers;

use App\Department;
use App\Document;
use App\Office;
use App\PrivateDocsVisitor;
use App\RequestApprover;
use App\ChangeRequest;
use App\CopyRequest;
use App\DocumentType;
use App\Company;
use App\ChangeRequestAccess;
use App\DocumentRequestAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
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
        $isAdmin = auth()->user()->role === 'Administrator';

        $baseQuery = $isAdmin
            ? ChangeRequest::query()
            : ChangeRequest::where('user_id', auth()->id());

        $totalCount = (clone $baseQuery)->count();
        $pendingCount  = (clone $baseQuery)->where('status', 'For Approval')->count();
        $approvedCount = (clone $baseQuery)->where('status', 'Approved')->count();
        $declinedCount = (clone $baseQuery)->where('status', 'Declined')->count();

        $donutTotal  = $totalCount ?: 1;
        $approvedPct = round(($approvedCount / $donutTotal) * 100);
        $pendingPct  = round(($pendingCount  / $donutTotal) * 100);
        $declinedPct = round(($declinedCount / $donutTotal) * 100);
        $otherPct    = max(0, 100 - $approvedPct - $pendingPct - $declinedPct);

        $table_query = clone $baseQuery;

        if ($request->filled('doc_search')) {
            $search = $request->doc_search;
            $table_query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('file', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('doc_status') && $request->doc_status !== 'Default') {
            $table_query->where('status', $request->doc_status);
        }

        $sortBy = $request->get('doc_sort', 'creation');
        $perPage = in_array($request->get('doc_per_page'), ['25', '50', '100'])
            ? (int) $request->get('doc_per_page')
            : 10;

        if ($sortBy === 'name') {
            $table_query->orderBy('title', 'asc');
        } elseif ($sortBy === 'date') {
            $table_query->orderBy('updated_at', 'desc');
        } else {
            $table_query->orderBy('created_at', 'desc');
        }

        $change_requests = $table_query->paginate($perPage, ['*'], 'table_page');

        $year = date('Y');

        $monthlySubmitted = [];
        $monthlyApproved  = [];
        $monthlyDeclined  = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlySubmitted[] = (clone $baseQuery)->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
            $monthlyApproved[]  = (clone $baseQuery)->where('status', 'Approved')->whereYear('updated_at', $year)->whereMonth('updated_at', $m)->count();
            $monthlyDeclined[]  = (clone $baseQuery)->where('status', 'Declined')->whereYear('updated_at', $year)->whereMonth('updated_at', $m)->count();
        }

        $allDepts = Department::where('status', '1')->orderBy('code')->get();

        $submittedByDept = (clone $baseQuery)
            ->selectRaw('department_id, count(*) as total')
            ->whereNotNull('department_id')
            ->groupBy('department_id')
            ->pluck('total', 'department_id');

        $deptLabels = $allDepts->pluck('code')->toArray();
        $deptCounts = $allDepts->map(fn($d) => $submittedByDept->get($d->id, 0))->values()->toArray();

        $typeData = (clone $baseQuery)
            ->selectRaw('type, count(*) as total')
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderByDesc('total')
            ->with('document_type')
            ->limit(6)
            ->get();

        $typeLabels = $typeData->map(fn($r) => optional($r->document_type)->name ?? 'Unknown')->values()->toArray();
        $typeCounts = $typeData->pluck('total')->values()->toArray();

        $weekStart = now()->startOfWeek();
        $weekEnd   = now()->endOfWeek();

        $weeklyUploaded = (clone $baseQuery)->whereBetween('created_at', [$weekStart, $weekEnd])->count();
        $weeklyApproved = (clone $baseQuery)->where('status', 'Approved')->whereBetween('updated_at', [$weekStart, $weekEnd])->count();
        $weeklyDeclined = (clone $baseQuery)->where('status', 'Declined')->whereBetween('updated_at', [$weekStart, $weekEnd])->count();

        $weeklyUploadedByDay = [];
        $weeklyApprovedByDay = [];

        for ($d = 0; $d < 7; $d++) {
            $day = $weekStart->copy()->addDays($d);
            $weeklyUploadedByDay[] = (clone $baseQuery)->whereDate('created_at', $day)->count();
            $weeklyApprovedByDay[] = (clone $baseQuery)->where('status', 'Approved')->whereDate('updated_at', $day)->count();
        }

        return view('home', [
            'change_requests' => $change_requests,
            'totalCount' => $totalCount,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'declinedCount' => $declinedCount,
            'monthlySubmitted' => $monthlySubmitted,
            'monthlyApproved' => $monthlyApproved,
            'monthlyDeclined' => $monthlyDeclined,
            'deptLabels' => $deptLabels,
            'deptCounts' => $deptCounts,
            'typeLabels' => $typeLabels,
            'typeCounts' => $typeCounts,
            'weeklyUploaded' => $weeklyUploaded,
            'weeklyApproved' => $weeklyApproved,
            'weeklyDeclined' => $weeklyDeclined,
            'weeklyUploadedByDay' => $weeklyUploadedByDay,
            'weeklyApprovedByDay' => $weeklyApprovedByDay,
            'approvedPct' => $approvedPct,
            'pendingPct'  => $pendingPct,
            'declinedPct' => $declinedPct,
            'otherPct'    => $otherPct,
        ]);
    }

    public function confirmPassword(Request $request)
    {
        $password = auth()->user()->password;

        if (Hash::check($request->password, $password)) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function search(Request $request)
    {
        $documents = [];
        $off=$request->off;
        $dept = $request->department;
        $search = $request->search;

        $offices = Office::where("status","Active")->get();
        $departments = Department::whereNull("status")->get();
        
        $request_documents = Document::where('public','!=',null)->where('status',null)->orderBy('control_code','asc')->get();
        $documents_filter = Document::query();
        if($request->department)
        {
            $documents = $documents_filter->where('department_id',$request->department)->get();
        }
        if($request->office)
        {
            // $documents = $documents_filter->where('off',$request->company)->orderBy('old_control_code', 'DESC')->get();
        }
        if($request->search)
        {
            if($request->department) {
                $documents = $documents_filter->where("control_code", "LIKE","%".$request->search."%")
                                            ->orWhere("title", "LIKE","%".$request->search."%")
                                            ->where("department_id", $request->department)
                                            ->get();
            }
            else {
                $documents = $documents_filter->where("control_code", "LIKE","%".$request->search."%")->orWhere("title", "LIKE","%".$request->search."%")->get();
            }
        }

        return view('search',
        array(
            'documents' => $documents,
            'search' => $request->search,
            'request_documents' => $request_documents,
            'offices' => $offices,
            'departments' => $departments,
            // 'comp' => $comp,
            'dept' => $dept
        ));
    }

    public function recordChangeRequestView(Request $request)
    {
        PrivateDocsVisitor::create([
            'change_request_id' => $request->change_request_id,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json(['success' => true]);
    }

    public function changeRequestVisitors($id)
    {
        $changeRequest = ChangeRequest::with(['visitors.user.department'])->findOrFail($id);
        return view('change_request.visitors', compact('changeRequest'));
    }

    public function requestAccess(Request $request,$id){
        // dd($request->all(),$id);
        $request->validate([
            'user_id' => 'numeric',
            'reason' => 'string|required',
            'date' => 'nullable|date'
        ]);

        $document_request_access = DocumentRequestAccess::where("change_request_id", $id)->where("status", 0)->first();
        if (empty($document_request_access)) {
            $document_request_access = new DocumentRequestAccess;
            $document_request_access->change_request_id = $id;
            $document_request_access->reason = $request->reason;
            $document_request_access->user_id = $request->user_id;
            $document_request_access->request_date = $request->date;
            $document_request_access->status = 0;
            $document_request_access->requestor_id = auth()->user()->id;
            $document_request_access->save();

            Alert::success("Successfully Saved")->persistent("Dismiss");
        }
        else {
            Alert::warning("You have a pending permission in this document")->persistent("Dismiss");
        }

        return back();
    }

    public function forRequestAccess()
    {
        if (!canView('access_request.view')) {
            return view('pages.403-error');
        }

        $base = DocumentRequestAccess::where('user_id', auth()->id());

        $forApproval = (clone $base)->where('status', 0)->count();
        $approved = (clone $base)->where('status', 1)->count();
        $declined = (clone $base)->where('status', 3)->count();

        return view('for_request_access', compact('forApproval', 'approved', 'declined'));
    }

    public function getRequestAccessData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $statusFilter = $request->get('status_filter');

        $query = DocumentRequestAccess::with(['changeRequest.user.department', 'requestor.department'])
            ->where('user_id', auth()->id());

        $totalRecords = (clone $query)->count();

        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('status', (int) $statusFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('requestor', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })
                ->orWhereHas('changeRequest', function ($q3) use ($search) {
                    $q3->where('title', 'like', "%$search%");
                })
                ->orWhere('reason', 'like', "%$search%");
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $access) {

            if ($access->status == 0) {
                $statusBadge = '<span class="badge bg-warning text-dark">For Approval</span>';
            } elseif ($access->status == 1) {
                $statusBadge = '<span class="badge bg-success">Approved</span>';
            } else {
                $statusBadge = '<span class="badge bg-danger">Declined</span>';
            }

            $dept = optional(optional($access->requestor)->department)->name;
            $department = $dept
                ? '<span class="badge bg-info-subtle text-info"><i class="ri-building-line me-1"></i>' . e($dept) . '</span>'
                : '<span class="text-muted">—</span>';

            if ($access->status == 0) {
                $action = '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                        <ul class="dropdown-menu">
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#approveModal' . $access->id . '">
                                    <i class="ri-check-line me-2"></i>Approve
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#declineModal' . $access->id . '">
                                    <i class="ri-close-line me-2"></i>Decline
                                </button>
                            </li>
                        </ul>
                    </div>';
            } else {
                $action = '<span class="text-muted small">—</span>';
            }

            $modalHtml = '';
            if ($access->status == 0) {
                $requestorName = e(optional($access->requestor)->name ?? '—');
                $docTitle = e(optional($access->changeRequest)->title ?? '—');
                $approveUrl = url('request_access_approved/' . $access->id);
                $declineUrl = url('request_access_declined/' . $access->id);
                $csrfToken = csrf_token();
                $today = date('Y-m-d');

                $modalHtml = '
                <div class="modal fade" id="approveModal' . $access->id . '" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="' . $approveUrl . '" method="POST">
                                <input type="hidden" name="_token" value="' . $csrfToken . '">
                                <input type="hidden" name="status" value="1">
                                <div class="modal-header border-0 border-bottom pb-0">
                                    <div class="mb-2">
                                        <h5 class="modal-title">Approve Request</h5>
                                        <small class="text-muted">' . $requestorName . ' &mdash; ' . $docTitle . '</small>
                                    </div>
                                    <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body pt-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Access Until <span class="text-muted fw-normal">(optional)</span></label>
                                        <input type="date" name="access_until" class="form-control" min="' . $today . '">
                                        <div class="form-text">Leave blank to grant indefinite access.</div>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label fw-semibold">Notes <span class="text-muted fw-normal">(optional)</span></label>
                                        <textarea name="approve_notes" class="form-control" rows="3" placeholder="Add any notes for this approval..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 border-top pt-0">
                                    <button type="button" class="btn btn-sm btn-secondary mt-3" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-primary mt-3 px-4"><i class="ri-check-line me-1"></i> Confirm Approve</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="declineModal' . $access->id . '" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="' . $declineUrl . '" method="POST">
                                <input type="hidden" name="_token" value="' . $csrfToken . '">
                                <input type="hidden" name="status" value="3">
                                <div class="modal-header border-0 border-bottom pb-0">
                                    <div class="mb-2">
                                        <h5 class="modal-title mb-0">Decline Request</h5>
                                        <small class="text-muted">' . $requestorName . ' &mdash; ' . $docTitle . '</small>
                                    </div>
                                    <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body pt-3">
                                    <div class="mb-1">
                                        <label class="form-label fw-semibold">Reason for Declining <span class="text-muted fw-normal">(optional)</span></label>
                                        <textarea name="decline_reason" class="form-control" rows="3" placeholder="Provide a reason for declining this request..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 border-top pt-0">
                                    <button type="button" class="btn btn-light mt-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger mt-2 px-4"><i class="ri-close-line me-1"></i> Confirm Decline</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';
            }

            $data[] = [
                'action' => $action,
                'doc_id' => $access->changeRequest
                        ? '<span class="ref-badge">DOC-' . date('Y', strtotime($access->changeRequest->created_at)) . '-' . str_pad($access->changeRequest->id, 3, '0', STR_PAD_LEFT) . '</span>'
                        : '—',
                'requested_by' => '
                            <div class="fw-semibold">' . e(optional($access->requestor)->name ?? '—') . '</div>
                            <small class="text-muted">
                                <i class="ri-building-line me-1"></i>' . e(optional(optional($access->requestor)->department)->name ?? '—') . '
                            </small>
                        ',
                        'department' => $department,
                'title' => '
                            <div class="fw-semibold">' . e(optional($access->changeRequest)->title ?? '—') . '</div>
                            <small class="text-muted">
                                <i class="ri-user-line me-1"></i>' . e(optional(optional($access->changeRequest)->user)->name ?? '—') . ' &mdash;
                                <i class="ri-building-line me-1"></i>' . e(optional(optional(optional($access->changeRequest)->user)->department)->name ?? '—') . '
                            </small>
                        ',
                'date' => $access->request_date
                                    ? \Carbon\Carbon::parse($access->request_date)->format('M d, Y')
                                    : '—',
                'reason' => e($access->reason),
                'status' => $statusBadge,
                'modal_html' => $modalHtml,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function requestAccessApproved(Request $request, $id) 
    {
        // dd($request->all());
        $document_request_access = DocumentRequestAccess::findOrFail($id);
        $document_request_access->status = $request->status;
        $document_request_access->approve_notes = $request->approve_notes ?? null;
        $document_request_access->access_until  = $request->access_until ?? null;
        $document_request_access->save();
 
        Alert::success("Successfully Approved")->persistent("Dismiss");
        return back();
    }

    public function requestAccessDeclined(Request $request, $id) 
    {
        $document_request_access = DocumentRequestAccess::findOrFail($id);
        $document_request_access->status = $request->status;
        $document_request_access->decline_reason = $request->decline_reason ?? null;
        $document_request_access->save();
 
        Alert::success("Successfully Declined")->persistent("Dismiss");
        return back();
    }
}