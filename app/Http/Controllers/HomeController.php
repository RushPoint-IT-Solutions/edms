<?php

namespace App\Http\Controllers;

use App\Permit;
use App\Department;
use App\Document;
use App\ChangeRequest;
use App\CopyRequest;
use App\DocumentType;
use App\Company;
use App\Office;
use App\PrivateDocsVisitor;
use App\RequestApprover;
use App\ChangeRequestAccess;
use App\DocumentRequestAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (auth()->user()->role != "Administrator")
        {
            // $pending_query = ChangeRequest::where(function($q) {
            //                     $q->where('user_id', auth()->user()->id)
            //                     ->where('status', 'For Approval')
            //                     ->where('request_status', 'Pending');
            //                 })->orWhere(function($q) {
            //                     $q->whereHas('approvers', function($aq) {
            //                         $aq->where('user_id', auth()->user()->id)
            //                         ->whereIn('status', ['Pending', 'Waiting']);
            //                     })
            //                     ->whereNotIn('status', ['Approved', 'Declined'])
            //                     ->whereNull('is_draft');
            //                 });

            $table_query = ChangeRequest::where('user_id', auth()->user()->id);
        }
        else
        {
            $table_query = ChangeRequest::query();
        }

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

        if ($sortBy == 'name') {
            $table_query->orderBy('title', 'asc');
        } elseif ($sortBy == 'date') {
            $table_query->orderBy('updated_at', 'desc');
        } else {
            $table_query->orderBy('created_at', 'desc');
        }

        // $copy_requests = CopyRequest::get();

        // $yearChangeRequests = ChangeRequest::whereYear('created_at',date('Y'))->get();
        // $yearCopyRequests = CopyRequest::whereYear('created_at',date('Y'))->get();
        // $documents = Document::where('status',null)->get();
        // $departments = Department::whereHas('documents')->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        // $permits = Permit::with('company', 'department')->get();
        // $months = [];
       
        // for ($m=1; $m<=12; $m++) {
        //     $object = new \stdClass();
        //     $object->y =date('M-Y', mktime(0,0,0,$m, 1, date('Y')));
        //     $change_requests_count = ChangeRequest::whereYear('created_at',date('Y'))->whereMonth('created_at',date('m',mktime(0,0,0,$m, 1, date('Y'))))->count();
        //     $copy_requests_count = CopyRequest::whereYear('created_at',date('Y'))->whereMonth('created_at',date('m',mktime(0,0,0,$m, 1, date('Y'))))->count();
        //     $object->a =$change_requests_count;
        //     $object->b =$copy_requests_count;
        //     $months[$m-1]=  $object;
        // }
        // dd($months);
        // if((auth()->user()->role != "Administrator") || (auth()->user()->role != "Management Representative") || (auth()->user()->role != "Business Process Manager"))
        // {
        //     if((auth()->user()->role == "Department Head"))
        //     {
        //         $departments = Department::whereIn('id',(auth()->user()->department_head)->pluck('id')->toArray())->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        //         $change_requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
        //         $copy_requests = CopyRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
        //         $documents = Document::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->where('status',null)->toArray())->get();
        //         $permits = Permit::with('company', 'department')->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
           
        //     }
        //     elseif((auth()->user()->role == "Documents and Records Controller"))
        //     {
        //         $departments = Department::where('id',auth()->user()->department_id)->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        //         $change_requests = ChangeRequest::where('user_id',auth()->user()->id)->get();
        //         $copy_requests = CopyRequest::where('user_id',auth()->user()->id)->get();
        //         $documents = Document::where('department_id',auth()->user()->department_id)->where('status',null)->get();
        //         $permits = Permit::with('company', 'department')->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
           

        //     }
        //     elseif((auth()->user()->role == "Document Control Officer"))
        //     {
        //     }
            
        // }
        // $departments = Department::whereIn('id',(auth()->user()->dco)->pluck('department_id')->toArray())->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        // // $change_requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
        // $change_requests = ChangeRequest::with('user')->get();
        // $copy_requests = CopyRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
        // $documents = Document::with('change_requests')->where('user_id', auth()->user()->id)->get();
        // $permits = Permit::with('company', 'department')->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();

        // $categories = DocumentType::get();

        $change_requests = $table_query->paginate($perPage, ['*'], 'table_page');

        return view('home',
        array(
            // 'permits' =>  $permits,
            'change_requests' =>  $change_requests,
            // 'categories' =>  $categories,
            // 'copy_requests' =>  $copy_requests,
            // 'months' =>  $months,
            // 'yearChangeRequests' =>  $yearChangeRequests,
            // 'yearCopyRequests' =>  $yearCopyRequests,
        ));
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

    // public function requestChangeRequestAccess(Request $request)
    // {
    //     $existing = \App\ChangeRequestAccess::where('change_request_id', $request->change_request_id)
    //         ->where('user_id', auth()->user()->id)
    //         ->first();

    //     if ($existing) {
    //         return response()->json([
    //             'success' => false,
    //             'status'  => $existing->status,
    //             'message' => 'You already have a ' . $existing->status . ' request for this document.'
    //         ]);
    //     }

    //    ChangeRequestAccess::create([
    //         'change_request_id' => $request->change_request_id,
    //         'user_id'           => auth()->user()->id,
    //         'status'            => 'Pending',
    //     ]);

    //     return response()->json(['success' => true, 'message' => 'Access request submitted successfully.']);
    // }

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
            'date' => 'required|date'
        ]);

        $document_request_access = DocumentRequestAccess::where("document_id", $id)->where("status", 0)->first();
        if (empty($document_request_access)) {
            $document_request_access = new DocumentRequestAccess;
            $document_request_access->document_id = $id;
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
        $base = DocumentRequestAccess::where('user_id', auth()->id());

        $forApproval = (clone $base)->where('status', 0)->count();
        $approved    = (clone $base)->where('status', 1)->count();
        $declined    = (clone $base)->where('status', 3)->count();

        return view('for_request_access', compact('forApproval', 'approved', 'declined'));
    }

    public function getRequestAccessData(Request $request)
    {
        $draw         = $request->get('draw');
        $start        = $request->get('start');
        $length       = $request->get('length');
        $search       = $request->get('search')['value'] ?? '';
        $statusFilter = $request->get('status_filter');

        $query = DocumentRequestAccess::with(['document', 'requestor.department'])
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
                ->orWhereHas('document', function ($q3) use ($search) {
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
                $docTitle      = e(optional($access->document)->title ?? '—');
                $approveUrl    = url('request_access_approved/' . $access->id);
                $declineUrl    = url('request_access_declined/' . $access->id);
                $csrfToken     = csrf_token();
                $today         = date('Y-m-d');

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
                                    <button type="button" class="btn btn-light mt-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success mt-2 px-4"><i class="ri-check-line me-1"></i> Confirm Approve</button>
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
                'action'       => $action,
                'requested_by' => e(optional($access->requestor)->name ?? '—'),
                'department'   => $department,
                'title'        => e(optional($access->document)->title ?? '—'),
                'date'         => $access->request_date
                                    ? \Carbon\Carbon::parse($access->request_date)->format('M d, Y')
                                    : '—',
                'reason'       => e($access->reason),
                'status'       => $statusBadge,
                'modal_html'   => $modalHtml,
            ];
        }

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
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