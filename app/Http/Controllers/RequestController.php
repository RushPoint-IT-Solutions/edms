<?php

namespace App\Http\Controllers;
use App\Document;
use App\Department;
use App\Company;
use App\DepartmentApprover;
use App\CopyRequest;
use App\ChangeRequest;
use App\Comment;
use App\DocumentAttachment;
use App\CopyApprover;
// use App\DateApprovedLog;
use App\DepartmentDco;
use App\DocumentRequestAccess;
use App\DocumentSignaturePosition;
use App\Notifications\NewPreAssessment;
use App\RequestApprover;
use App\ObsoleteAttachment;
use App\Obsolete;
use App\DocumentType;
use App\History;
use App\Team;
use App\Mail\ApprovedRequestEmail;
use App\Mail\RequestDocumentApproval;
use App\Mail\ReturnedRequestEmail;
use App\User;
use App\Notifications\ForApproval;
use App\Notifications\NewPolicy;
use App\Notifications\ApprovedRequest;
use App\Notifications\DeclineRequest;
use App\Notifications\ReturnRequest;
use App\Notifications\PendingRequest;
use App\PreAssessment;
use App\PreAssessmentApprover;
use App\SupportingDocument;
use App\UserNotification;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function editTile (Request $request,$id)
    // {
    //     $change_request = ChangeRequest::findOrfail($id);
    //     $change_request->title = $request->title;
    //     $change_request->type_of_document = $request->document_type;
    //     $change_request->save();
    //     Alert::success('Successfully Updated')->persistent('Dismiss');
    //     return back();
    // }
    // public function editRequest (Request $request,$id)
    // {
    //     $req = ChangeRequest::findOrfail($id);
    //     $req->change_request = $request->description;
    //     $req->indicate_clause = $request->from_clause;
    //     $req->indicate_changes = $request->to_changes;
    //     $req->save();

    //     Alert::success('Successfully Updated')->persistent('Dismiss');
    //     return back();
    // }
    // public function test()
    // {
    //       info("START DCO");
    //     $users = User::where('status',null)->where('role','Document Control Officer')->get();
    //     foreach($users as $user)
    //     {
    //         $change_requests = ChangeRequest::with('approvers')->whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->where('status','Pending')->get();

    //         $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>Date Requested</th><th>Code</th><th>Approver</th></tr>";
    //         foreach($change_requests as $request)
    //         {
    //             $approver = ($request->approvers)->where('level',$request->level)->first();
    //             $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
    //         }
    //         $table .= "</table>";
    //         if(count($change_requests) >0)
    //         {
    //             $user->notify(new PendingRequest($table));
    //         }
           
    //     }
    //     $users_d = User::where('status',null)->where('role','Business Process Manager')->orWhere('role','Management Representative')->get();
    //     foreach($users_d as $user)
    //     {
    //         $change_requests = ChangeRequest::with('approvers')->where('status','Pending')->get();

    //         $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>Date Requested</th><th>Code</th><th>Approver</th></tr>";
    //         foreach($change_requests as $request)
    //         {
    //             $approver = ($request->approvers)->where('level',$request->level)->first();
    //             $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
    //         }
    //         $table .= "</table>";
    //         if(count($change_requests) >0)
    //         {
    //             $user->notify(new PendingRequest($table));
    //         }
    //     }

    //     $users_approvers = User::where('status',null)->get();
    //     foreach($users_approvers as $user)
    //     {
    //         $change_requests = ChangeRequest::whereHas('approvers',function($q) use($user){
    //             $q->where('user_id',  $user->id)->where('status','Pending');
    //         })->where('status','Pending')->get();

    //         $copy_requests = CopyRequest::whereHas('approvers',function($q) use($user){
    //             $q->where('user_id',  $user->id)->where('status','Pending');
    //         })->where('status','Pending')->get();

    //         $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th colspan='3'>For Your Approval</th></tr>";
    //         if(count($change_requests) > 0)
           
    //         {
    //             $table .= "<tr><th colspan='3'>Change Requests</th></tr>";
    //         }
    //         $table .= "<tr><th>Date Requested</th><th>Code</th><th>Approver</th></tr>";
    //         foreach($change_requests as $request)
    //         {
    //             $approver = ($request->approvers)->where('level',$request->level)->first();
    //             $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>DICR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
    //         }
    //         if(count($copy_requests) > 0)
    //         {
    //             $table .= "<tr><th colspan='3'>Copy Requests</th></tr>";
    //         }
    //             foreach($copy_requests as $request)
    //             {
    //                 $approver = ($request->approvers)->where('level',$request->level)->first();
    //                 $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
    //             }
        
            
    //         $table .= "</table>";

    //         if((count($change_requests) >0) ||(count($copy_requests) >0))
    //         {
    //             $user->notify(new PendingRequest($table));
    //         }
    //     }
      
    //     info("END DCO");
    // }
    // public function index()
    // {
    //     //
       
    //     $requests = CopyRequest::with('document')->orderBy('id','desc')->get();
    //     if(auth()->user()->role == "User")
    //     {
    //         $requests = CopyRequest::with('document')->where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
    //     }
    //     else if(auth()->user()->role == "Document Control Officer")
    //     {
    //         $requests = CopyRequest::with('document')->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
    //     }
    //     else if(auth()->user()->role == "Department Head")
    //     {
    //         $requests = CopyRequest::with('document')->whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orderBy('id','desc')->get();
    //     }
    //     else if(auth()->user()->role == "Documents and Records Controller")
    //     {
    //         $requests = CopyRequest::with('document')->where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
    //     }
    //     return view('requests',
    //     array(
    //         'requests' =>  $requests,
    //     ));
    // }

    public function changeRequests(Request $request)
    {
        // dd($request->all());
        $isAdmin = in_array(auth()->user()->role, ['Administrator', 'Approver']);

        $query = ChangeRequest::whereNull('is_draft')
            ->when(!$isAdmin, function ($q) {
                $q->where('user_id', auth()->user()->id);
            });

        $forApprovalCount = (clone $query)->where('status', 'For Approval')->count();
        $declinedCount    = (clone $query)->where('status', 'Declined')->count();
        $approvedCount    = (clone $query)->where('status', 'Approved')->count();
        $returnedCount    = (clone $query)->where('status', 'Returned')->count();

        return view('change_request.change_requests', [
            'forApprovalCount' => $forApprovalCount,
            'declinedCount'    => $declinedCount,
            'approvedCount'    => $approvedCount,
            'returnedCount'    => $returnedCount
        ]);
    }

    public function getChangeRequestsData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $order = $request->get('order')[0] ?? ['column' => 8, 'dir' => 'desc'];
        $columnIndex = $order['column'];
        $columnName = $request->get('columns')[$columnIndex]['data'] ?? 'created_at';
        $columnSortOrder = $order['dir'];
        $statusFilter = $request->get('status', '');

        $isAdmin = in_array(auth()->user()->role, ['Administrator', 'Approver']);

        $query = ChangeRequest::with(['user', 'approvers.user'])
            ->whereNull('is_draft')
            ->when(!$isAdmin, function ($q) {
                $q->where('user_id', auth()->user()->id);
            });

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description','like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('privacy', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $totalFiltered = $query->count();

        $allowed = ['title', 'description', 'category', 'privacy', 'revision', 'created_at', 'status'];
        if (in_array($columnName, $allowed)) {
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }

        $changeRequests = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($changeRequests as $cr) {

            switch ($cr->status) {
                case 'Approved': $badgeClass = 'bg-success'; break;
                case 'For Approval': $badgeClass = 'bg-primary'; break;
                case 'Declined': $badgeClass = 'bg-danger'; break;
                case 'Returned': $badgeClass = 'bg-warning text-dark'; break;
                case 'Draft': $badgeClass = 'bg-secondary'; break;
                default: $badgeClass = 'bg-secondary'; break;
            }
            $statusBadge = '<span class="badge ' . $badgeClass . '">' . e($cr->status) . '</span>';

            $docId = 'DOC-' . date('Y', strtotime($cr->created_at)) . '-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT);

            $approversChain = '<div class="approvers-chain">';
            foreach ($cr->approvers->sortBy('level') as $approver) {
                $user = $approver->user;
                if (!$user) continue;

                switch ($approver->status) {
                    case 'Approved':
                        $icon = 'ri-checkbox-circle-fill'; $color = '#198754'; $badgeClass = 'bg-success'; break;
                    case 'Pending':
                        $icon = 'ri-time-line'; $color = '#e67e22'; $badgeClass = 'bg-warning text-dark'; break;
                    case 'Returned':
                        $icon = 'ri-arrow-go-back-fill'; $color = '#dc3545'; $badgeClass = 'bg-danger'; break;
                    case 'Declined':
                        $icon = 'ri-close-circle-fill'; $color = '#dc3545'; $badgeClass = 'bg-danger'; break;
                    case 'Waiting':
                        $icon = 'ri-checkbox-blank-circle-line'; $color = '#6c757d'; $badgeClass = 'bg-secondary'; break;
                    default:
                        $icon = 'ri-question-line'; $color = '#adb5bd'; $badgeClass = 'bg-light text-dark'; break;
                }

                $approversChain .= '
                <div class="approver-step mb-1" style="white-space:nowrap;">
                    <div class="d-flex align-items-center gap-1" style="flex-wrap:nowrap;">
                        <i class="' . $icon . '" style="color:' . $color . '; font-size:0.9rem; flex-shrink:0;"></i>
                        <span style="font-size:0.78rem; font-weight:600; color:#212529; flex-shrink:0;">' . e($user->name) . '</span>
                        <span class="badge ' . $badgeClass . '" style="font-size:0.65rem; flex-shrink:0;">' . e($approver->status) . '</span>
                    </div>
                </div>';

                if (!$cr->approvers->sortBy('level')->last()->is($approver)) {
                    $approversChain .= '<div style="margin-left:8px; color:#adb5bd; font-size:0.7rem; margin-bottom:3px;">&#8595;</div>';
                }
            }
            $approversChain .= '</div>';

            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="' . url('change-request/' . $cr->id) . '">
                                <i class="ri-information-line me-2"></i> View Status
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="' . url('change-request/view-change-request/' . $cr->id) . '">
                                <i class="ri-eye-line me-2"></i> View Request
                            </a>
                        </li>
                    </ul>
                </div>
            ';

            $data[] = [
                'action' => $actions,
                'doc_id' => $docId,
                'title' => e($cr->title),
                'description' => e($cr->description),
                'category' => e($cr->category),
                'privacy' => e($cr->privacy),
                'revision' => e($cr->revision),
                'requested_by' => $cr->user->name ?? 'N/A',
                'created_at' => $cr->created_at ? $cr->created_at->format('Y-m-d') : '-',
                'approvers'  => $approversChain,
                'status' => $statusBadge,
                'qr_code' => '
                    <button class="btn btn-sm btn-outline-primary view-qr-btn"
                        data-doc-id="' . $docId . '"
                        data-doc-title="' . e($cr->title) . '"
                        data-change-request-id="' . $cr->id . '">
                        <i class="ri-qr-code-line"></i> View QR
                    </button>
                ',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
    

    // public function removeApprover()
    // {
    //     //
       
    //     $change_for_approvals = RequestApprover::orderBy('id','desc')->get();
       

    //     return view('for_removals',
    //     array(
    //        'change_for_approvals' => $change_for_approvals,
    //     ));
    // }
    // public function removeApp(Request $request,$id)
    // {
    //     if($request->approver == null)
    //     {
    //         $appro = [];
    //     }
    //     else
    //     {
    //         $appro = $request->approver;
    //     }
    //     $approvers = RequestApprover::orderBy('id','desc')->where('change_request_id',$id)->whereNotIn('id', $appro)->where('status','Waiting')->delete();
    //     Alert::success('Successfully Updated')->persistent('Dismiss');
    //     return back();
        
    // }
    public function forApproval()
    {
        //
        $document_types = DocumentType::get();
        // $copy_for_approvals = CopyApprover::with('copy_request.document.attachments')->orderBy('id','desc')->where('user_id',auth()->user()->id)->get();
        $change_for_approvals = RequestApprover::with('change_request.document_type')->orderBy('id','desc')->where('user_id',auth()->user()->id)->get();
        if(auth()->user()->role == "Administrator")
        {
            // $copy_for_approvals = CopyApprover::with('copy_request.document.attachments')->orderBy('id','desc')->get();
            $change_for_approvals = RequestApprover::with('change_request.document_type')->orderBy('id','desc')->get();
        }
       
        // $document_request_access = DocumentRequestAccess::where("status", 0)->where("user_id", auth()->id())->get();

        return view('for_approval',
        array(
        //    'copy_for_approvals' => $copy_for_approvals,
           'change_for_approvals' => $change_for_approvals,
           'document_types' => $document_types,
        //    'document_request_access' => $document_request_access,
        ));
    }

    public function getChangeApprovalsData(Request $request)
    {
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = ChangeRequest::with(['approvers.user', 'user', 'document_type'])
            ->whereNull('is_draft')
            ->whereHas('approvers', function ($q) {
                $q->where('status', 'Pending')
                ->orWhere('status', 'Waiting');
            })
            ->whereNotIn('status', ['Approved', 'Declined'])
            ->when(auth()->user()->role !== 'Administrator', function ($q) {
                $q->whereHas('approvers', function ($aq) {
                    $aq->where('user_id', auth()->user()->id)
                    ->whereIn('status', ['Pending', 'Waiting']);
                });
            });

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $changeRequests = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($changeRequests as $cr) {
            $docId = 'DOC-' . date('Y', strtotime($cr->created_at)) . '-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT);

            $approvers = '<div class="approvers-chain">';
            foreach ($cr->approvers->sortBy('level') as $approver) {
                $user = $approver->user;
                if (!$user) continue;

                switch ($approver->status) {
                    case 'Approved':
                        $icon       = 'ri-checkbox-circle-fill';
                        $color      = '#198754';
                        $badgeClass = 'bg-success';
                        break;
                    case 'Pending':
                        $icon       = 'ri-time-line';
                        $color      = '#e67e22';
                        $badgeClass = 'bg-warning text-dark';
                        break;
                    case 'Returned':
                        $icon       = 'ri-arrow-go-back-fill';
                        $color      = '#dc3545';
                        $badgeClass = 'bg-danger';
                        break;
                    case 'Declined':
                        $icon       = 'ri-close-circle-fill';
                        $color      = '#dc3545';
                        $badgeClass = 'bg-danger';
                        break;
                    case 'Waiting':
                        $icon       = 'ri-checkbox-blank-circle-line';
                        $color      = '#6c757d';
                        $badgeClass = 'bg-secondary';
                        break;
                    default:
                        $icon       = 'ri-question-line';
                        $color      = '#adb5bd';
                        $badgeClass = 'bg-light text-dark';
                        break;
                }

                $approvers .= '
                <div class="approver-step mb-1" style="white-space:nowrap;">
                    <div class="d-flex align-items-center gap-1" style="flex-wrap:nowrap;">
                        <i class="' . $icon . '" style="color:' . $color . '; font-size:0.9rem; flex-shrink:0;"></i>
                        <span style="font-size:0.78rem; font-weight:600; color:#212529; flex-shrink:0;">'
                            . e($user->name) .
                        '</span> -
                        <span class="badge ' . $badgeClass . '" style="font-size:0.65rem; flex-shrink:0;">'
                            . e($approver->status) .
                        '</span>
                        ' . '
                    </div>
                </div>';

                if (!$cr->approvers->sortBy('level')->last()->is($approver)) {
                    $approvers .= '<div style="margin-left:8px; color:#adb5bd; font-size:0.7rem; margin-bottom:3px;">&#8595;</div>';
                }
            }
            $approvers .= '</div>';

            $myApprover = $cr->approvers->firstWhere('user_id', auth()->user()->id);
            $myStatus   = $myApprover ? $myApprover->status : null;
            $isMyTurn   = $myStatus === 'Pending';

            $disabledAttr  = $isMyTurn ? '' : 'disabled';
            $disabledClass = $isMyTurn ? '' : 'text-muted pe-none opacity-50';

            $data[] = [
                'action' => '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="' . url('change-request/for_approval/' . $cr->id) . '">
                                    <i class="ri-eye-line me-2"></i>View Request
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item ' . $disabledClass . '" 
                                    href="#"
                                    ' . ($isMyTurn ? 'onclick="openSignModal(' . $cr->id . ')"' : '') . '
                                    ' . $disabledAttr . '
                                    title="' . (!$isMyTurn ? 'Not your turn yet' : 'Sign this document') . '">
                                    <i class="ri-quill-pen-line me-2"></i>Sign Document
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item ' . $disabledClass . '"
                                    href="#"
                                    ' . ($isMyTurn ? 'onclick="openReturnModal(' . $cr->id . ')"' : '') . '
                                    ' . $disabledAttr . '
                                    title="' . (!$isMyTurn ? 'Not your turn yet' : 'Return this document') . '">
                                    <i class="ri-arrow-go-back-line me-2 text-danger"></i>Return Document
                                </a>
                            </li>
                        </ul>
                    </div>',

                'reference' => '<span class="ref-badge">' . $docId . '</span>',
                'date' => $cr->created_at ? $cr->created_at->format('M d, Y') : '-',
                'title' => e($cr->title),
                'requested_by' => $cr->user->name ?? 'N/A',
                'type' => optional($cr->document_type)->name ?? '-',
                'approvers' => $approvers,
                'status' => (function() use ($cr) {
                    switch ($cr->status) {
                        case 'For Approval': $badgeClass = 'bg-primary'; break;
                        case 'Draft': $badgeClass = 'bg-secondary'; break;
                        default: $badgeClass = 'bg-secondary'; break;
                    }
                    return '<span class="badge ' . $badgeClass . '">' . e($cr->status) . '</span>';
                })(),
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getCopyApprovalsData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $order = $request->get('order')[0] ?? ['column' => 2, 'dir' => 'desc'];
        $columnIndex = $order['column'];
        $columnName = $request->get('columns')[$columnIndex]['data'] ?? 'created_at';
        $columnSortOrder = $order['dir'];

        if (auth()->user()->role === 'Administrator') {
            $query = CopyApprover::with('copy_request.user')
                ->where('status', 'Pending');
        } else {
            $query = CopyApprover::with('copy_request.user')
                ->where('user_id', auth()->user()->id)
                ->where('status', 'Pending');
        }

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->whereHas('copy_request', function ($q) use ($search) {
                $q->where('title',         'like', "%{$search}%")
                  ->orWhere('control_code', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $query->orderBy('id', 'desc');

        $approvals = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($approvals as $approval) {
            $cr = $approval->copy_request;
            if (!$cr) continue;

            $data[] = [
                'action'       => '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#view_request_copy' . $cr->id . '">
                                    <i class="ri-eye-line me-2"></i>View Request
                                </a>
                            </li>
                        </ul>
                    </div>',
                'reference' => '<span class="ref-badge">CR-' . str_pad($cr->id, 5, '0', STR_PAD_LEFT) . '</span>',
                'date' => $cr->created_at ? $cr->created_at->format('M d, Y') : '-',
                'document' => '<small><strong>' . e($cr->control_code) . ' Rev. ' . e($cr->revision) . '</strong><br>' . e($cr->title) . '<br>' . e($cr->type_of_document) . '</small>',
                'requested_by' => $cr->user->name ?? 'N/A',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        $team = Team::find($request->privacy);
        
        // dd($request->all(), count(json_decode($request->signature_positions)));
        $request->validate([
            'file' => 'mimes:pdf'
        ]);

        try {
            DB::beginTransaction();
            
            if ($request->has('id'))
            {
                $change_request = ChangeRequest::findOrFail($request->id);
                $change_request->department_id = $request->department_id;
                $change_request->title = $request->title;
                $change_request->type = $request->type;
                $change_request->description = $request->description;
                $change_request->category = $request->category;
                $change_request->status = $request->status;
                $change_request->privacy = $team ? $team->name : null;
                $change_request->user_id = auth()->user()->id;
                $change_request->revision = 0;
                $change_request->request_status = "Pending";
                $change_request->due_date = $request->due_date ?: null;
                if($request->has('save_as_draft'))
                {
                    $change_request->is_draft = 1;
                }
                else
                {
                    $change_request->is_draft = null;
                }
                if ($request->hasFile('file'))
                {
                    $file = $request->file('file');
                    $name = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('attachment'),$name);
                    $change_request->file = '/attachment/'.$name;
                }
                $change_request->save();

                $approvers = RequestApprover::where('change_request_id', $change_request->id)->delete();
                foreach($request->approvers as $key=>$approver)
                {
                    $approvers = new RequestApprover;
                    $approvers->change_request_id = $change_request->id;
                    $approvers->user_id = $approver;
                    $approvers->level = $key+1;
                    if($key == 0)
                    {
                        $approvers->status = "Pending";
                        $approvers->start_date = date('Y-m-d');
                    }
                    else
                    {
                        $approvers->status = "Waiting";
                    }
                    $approvers->save();
                }

                if ($request->has('supporting_documents'))
                {
                    $supporting_documents = SupportingDocument::where('change_request_id', $change_request->id)->delete();
                    if ($request->hasFile('supporting_documents'))
                    {
                        $documents = $request->file('supporting_documents');
                        foreach($documents as $document)
                        {
                            $name = time().'_'.$document->getClientOriginalName();
                            $document->move(public_path('supporting_documents'),$name);
                
                            $supporting_documents = new SupportingDocument;
                            $supporting_documents->change_request_id = $change_request->id;
                            $supporting_documents->file = '/supporting_documents/'.$name;
                            $supporting_documents->save();
                        }
                    }
                }

                foreach(json_decode($request->signature_positions) as $signature_position)
                {
                    $document_signature_position = new DocumentSignaturePosition;
                    $document_signature_position->change_request_id = $change_request->id;
                    $document_signature_position->user_id = $signature_position->user_id;
                    $document_signature_position->page_number = $signature_position->page_number;
                    $document_signature_position->x_position = $signature_position->x_position;
                    $document_signature_position->y_position = $signature_position->y_position;
                    $document_signature_position->width = $signature_position->width;
                    $document_signature_position->height = $signature_position->height;
                    $document_signature_position->save();
                }
            }
            else 
            {
                $change_request = new ChangeRequest;
                $change_request->title = $request->title;
                $change_request->type = $request->type;
                $change_request->department_id = $request->department_id;
                $change_request->description = $request->description;
                $change_request->category = $request->category;
                $change_request->status = $request->status;
                $change_request->privacy = $team ? $team->name : null;
                $change_request->user_id = auth()->user()->id;
                $change_request->revision = 0;
                $change_request->request_status = "Pending";
                $change_request->due_date = $request->due_date ?: null;
                if($request->has('save_as_draft'))
                {
                    $change_request->is_draft = 1;
                }
                if ($request->hasFile('file'))
                {
                    $file = $request->file('file');
                    $name = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('attachment'),$name);
                    $change_request->file = '/attachment/'.$name;
                }
                $change_request->save();
        
                if ($request->has('approvers'))
                {
                    foreach($request->approvers as $key=>$approver)
                    {
                        $approvers = new RequestApprover;
                        $approvers->change_request_id = $change_request->id;
                        $approvers->user_id = $approver;
                        $approvers->level = $key+1;
                        if($key == 0)
                        {
                            $approvers->status = "Pending";
                            $approvers->start_date = date('Y-m-d');
                        }
                        else
                        {
                            $approvers->status = "Waiting";
                        }
                        $approvers->save();
                    }
                }
        
                if ($request->hasFile('supporting_documents'))
                {
                    $documents = $request->file('supporting_documents');
                    foreach($documents as $document)
                    {
                        $name = time().'_'.$document->getClientOriginalName();
                        $document->move(public_path('supporting_documents'),$name);
            
                        $supporting_documents = new SupportingDocument;
                        $supporting_documents->change_request_id = $change_request->id;
                        $supporting_documents->file = '/supporting_documents/'.$name;
                        $supporting_documents->save();
                    }
                }
            }

            if ($request->has('signature_positions'))
            {
                foreach(json_decode($request->signature_positions) as $signature_position)
                {
                    $document_signature_position = new DocumentSignaturePosition;
                    $document_signature_position->change_request_id = $change_request->id;
                    $document_signature_position->user_id = $signature_position->user_id;
                    $document_signature_position->page_number = $signature_position->page_number;
                    $document_signature_position->x_position = $signature_position->x_position;
                    $document_signature_position->y_position = $signature_position->y_position;
                    $document_signature_position->width = $signature_position->width;
                    $document_signature_position->height = $signature_position->height;
                    $document_signature_position->save();
                }
            }

            $users = User::whereIn('id', $request->approvers)->get()->pluck('email')->toArray();
            Mail::to($users)->send(new RequestDocumentApproval($change_request));

            DB::commit();

            Alert::success('Successfully Submitted')->persistent('Dismiss');
            return redirect('/change-requests');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("There is an error in adding change request: ". $e->getMessage());

            Alert::error('There is an error in creating request')->persistent('Dismiss');
            return redirect("/change-requests");
        }

    }

    // public function new_request(Request $request)
    // {
    //     //
    //     // dd($request->all());
    //     $request->validate([
    //         'supporting_document' => 'mimes:pdf',
    //         // 'category' => 'required',
    //         // 'reason_for_new_request' => 'required'
    //     ]);

    //     $preAssessment = new PreAssessment;
    //     $preAssessment->request_type = $request->request_type;
    //     $preAssessment->effective_date = $request->effective_date;
    //     $preAssessment->department_id = $request->department;
    //     $preAssessment->user_id = auth()->user()->id;
    //     $preAssessment->type_of_document = $request->category;
    //     $preAssessment->reason_for_changes = $request->reason_for_new_request;
    //     $preAssessment->change_request = $request->description;
    //     $preAssessment->supporting_documents = $request->supporting_document;
    //     $preAssessment->link_draft = $request->draft_link;
    //     $preAssessment->title = $request->title;
    //     $preAssessment->company_id = $request->company;
    //     $preAssessment->status = "Pending";

    //     if($request->has('soft_copy'))
    //     {
    //         $attachment = $request->file('soft_copy');
        
    //         $name = time() . '_' . $attachment->getClientOriginalName();
    //         $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
    //         $file_name = '/pre_assessment_attachments/' . $name;
    //         $preAssessment->soft_copy = $file_name;
    //     }
    //     if($request->has('pdf_copy'))
    //     {
    //         $attachment = $request->file('pdf_copy');
    //         $name = time() . '_' . $attachment->getClientOriginalName();
    //         $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
    //         $file_name = '/pre_assessment_attachments/' . $name;
    //         $preAssessment->pdf_copy = $file_name;
    //     }
    //     if($request->has('fillable_copy'))
    //     {
    //         $attachment = $request->file('fillable_copy');
    //         $name = time() . '_' . $attachment->getClientOriginalName();
    //         $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
    //         $file_name = '/pre_assessment_attachments/' . $name;
    //         $preAssessment->fillable_copy = $file_name;
    //     }
    //     if($request->has('supporting_document'))
    //     {
    //         $attachment = $request->file('supporting_document');
    //         $name = time() . '_' . $attachment->getClientOriginalName();
    //         $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
    //         $file_name = '/pre_assessment_attachments/' . $name;
    //         $preAssessment->supporting_documents = $file_name;
    //     }

    //     $preAssessment->save();

    //     $changeRequest = new ChangeRequest;
    //     $changeRequest->request_type = $request->request_type;
    //     $changeRequest->effective_date = $request->effective_date;
    //     $changeRequest->department_id = $request->department;
    //     $changeRequest->company_id = $request->company;
    //     $changeRequest->title = $request->title;
    //     $changeRequest->user_id = auth()->user()->id;
    //     $changeRequest->type_of_document = $request->category;
    //     $changeRequest->change_request = $request->description;
    //     $changeRequest->link_draft = $request->draft_link;
    //     $changeRequest->status = "Pending";
    //     $changeRequest->level = 1;
    //     $changeRequest->reason_for_changes = $request->reason_for_new_request;
    //     $changeRequest->pre_assessment_id = $preAssessment->id;
    //     if($request->has('soft_copy'))
    //     {
    //         $changeRequest->soft_copy = $preAssessment->soft_copy;
    //     }
    //     if($request->has('pdf_copy'))
    //     {
    //         $changeRequest->pdf_copy = $preAssessment->pdf_copy;
    //     }
    //     if($request->has('fillable_copy'))
    //     {
    //         $changeRequest->fillable_copy = $preAssessment->fillable_copy;
    //     }
    //     if($request->has('supporting_document'))
    //     {
    //         $changeRequest->supporting_documents = $preAssessment->supporting_documents ;
    //     }
        
    //     $changeRequest->save();

    //     $user = User::where('role', 'Document Control Officer')->where('status', null)->pluck('id')->toArray();
    //     $dco = DepartmentDco::where('department_id', auth()->user()->department_id)->whereIn('user_id', $user)->first();

    //     if ($dco != null)
    //     {
    //         $preAssessmentApprover = new PreAssessmentApprover;
    //         $preAssessmentApprover->pre_assessment_id = $preAssessment->id;
    //         $preAssessmentApprover->user_id = $dco->user_id;
    //         $preAssessmentApprover->status = "Pending";
    //         $preAssessmentApprover->start_date = date('Y-m-d');
    //         $preAssessmentApprover->save();

    //         $approvedRequestsNotif = User::where('id',$dco->user_id)->first();

    //         $approvedRequestsNotif->notify(new NewPreAssessment($preAssessment, "Pre-Assessment Approval"));
    //     }

    //     Alert::success('Successfully Submitted')->persistent('Dismiss');
    //     return redirect('/change-requests');
        
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $change_request = ChangeRequest::with('user','comments','approvers.user','supporting_documents')->findOrFail($id);
        // $dateLogs = DateApprovedLog::with('user')->where('change_request_id', $id)->orderBy('id', 'desc')->get();

        return view('change_request.view_approval', 
            array(
                'change_request' => $change_request,
                // 'dateLogs' => $dateLogs
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function action(Request $request,$id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            if ($request->action == "Submit")
            {
                $changeRequestApprover = RequestApprover::where('change_request_id', $id)->where('status', 'Returned')->first();
                $changeRequestApprover->status = "Pending";
                $changeRequestApprover->save();

                Alert::success('Successfully Submitted')->persistent('Dismiss');
                return redirect('/change-requests');
            }

            $changeRequestApprover = RequestApprover::where('change_request_id', $id)->where('user_id', auth()->user()->id)->first();
            $changeRequestApprover->status = $request->action;
            $changeRequestApprover->save();

            $requestApprover = RequestApprover::where('change_request_id',$changeRequestApprover->change_request_id)
            ->where(function ($query) {
                $query->where('status', 'Waiting')
                    ->orWhere('status', 'Returned');
            })
            ->orderBy('level','asc')->first();
            
            $changeRequest = ChangeRequest::findOrfail($id);
            if($request->action == "Approved")
            {
                if($requestApprover == null)
                {
                    $new_document = new Document;
                    $new_document->title = $changeRequest->title;
                    $new_document->category = $changeRequest->category;
                    $new_document->effective_date = date('Y-m-d');
                    $new_document->user_id = $changeRequest->user_id;
                    $new_document->version = 0;
                    $new_document->date_approved = date('Y-m-d');
                    $new_document->control_code = "DOC-".date('Y', strtotime($changeRequest->created_at)).'-'.str_pad($changeRequest->id,3,'0',STR_PAD_LEFT);
                    $new_document->save();

                    $changeRequest->document_id = $new_document->id;
                    $changeRequest->control_code = $new_document->control_code;
                    $changeRequest->revision = 0;
                    $changeRequest->status = "Approved";
                    if ($request->hasFile('file'))
                    {
                        $modified_file = $request->file('file');
                        $name = time().'_'.$modified_file->getClientOriginalName();
                        $modified_file->move(public_path('attachment'),$name);

                        $attachment = '/attachment/'.$name;

                        $changeRequest->file = $attachment;
                    }
                    $changeRequest->save();

                    $document_attachment = new DocumentAttachment;
                    $document_attachment->document_id = $new_document->id;
                    $document_attachment->attachment = $changeRequest->file;
                    $document_attachment->type = "pdf_copy";
                    $document_attachment->save();

                    // $approvedRequestsNotif = User::where('id',$copyRequest->user_id)->first();
                    // $approvedRequestsNotif->notify(new ApprovedRequest($copyRequest,"DICR-","Document Information Change Request","request"));

                    // $approvers_all = RequestApprover::where('change_request_id',$copyRequestApprover->change_request_id)->orderBy('level','asc')->get();
                    // foreach($approvers_all as $user_approver)
                    // {
                    //     $app = User::where('id',$user_approver->user_id)->first();
                    //     $app->notify(new NewPolicy($copyRequest,"DICR-","Document Information Change Request","request"));
                    // }
                }
                else
                {
                    $requestApprover->start_date = date('Y-m-d');
                    $requestApprover->status = "Pending";
                    $requestApprover->save();

                    if ($request->hasFile('file'))
                    {
                        $modified_file = $request->file('file');
                        $name = time().'_'.$modified_file->getClientOriginalName();
                        $modified_file->move(public_path('attachment'),$name);

                        $attachment = '/attachment/'.$name;

                        $changeRequest->file = $attachment;
                    }
                    $changeRequest->level = $changeRequest->level+1;
                    $changeRequest->save();
                }
                
                $comment = "<b>Update status: </b><span>".$request->old_status." &#x2192; ".$request->action."</span> <br> Remarks : ".$request->remarks."<br> ";
                $histories = new History;
                $histories->change_request_id = $changeRequest->id;
                $histories->comment = $comment;
                $histories->user_id = auth()->user()->id;
                $histories->save();

                $user = User::where('id',$changeRequest->user_id)->first();
                Mail::to($user)->send(new ApprovedRequestEmail($changeRequest));
                // Alert::success('Successfully Approved')->persistent('Dismiss');
                // return redirect('/for-approval');
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully Approved'
                ]);
            }
            elseif($request->action == "Returned")
            {
                $changeRequest->status = "Returned";
                $changeRequest->level = 1;
                $changeRequest->save(); 

                $comment = "<b>Update status: </b><span>".$request->old_status." &#x2192; ".$request->action."</span> <br> Remarks : ".$request->remarks."<br> ";
                $comments = new Comment;
                $comments->change_request_id = $changeRequest->id;
                $comments->comment = $comment;
                $comments->user_id = auth()->user()->id;
                $comments->save();

                $history = new History;
                $history->change_request_id = $changeRequest->id;
                $history->comment = $comment;
                $history->user_id = auth()->user()->id;
                $history->save();

                $user = User::where('id',$changeRequest->user_id)->first();
                Mail::to($user)->send(new ReturnedRequestEmail($changeRequest));

                Alert::success('Successfully Returned')->persistent('Dismiss');
                return redirect('/for-approval');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("There is an error in action of change request: ". $e->getMessage());
            
            Alert::error('There is an error in approval')->persistent('Dismiss');
            return redirect("/for-approval");
        }
    }
    
    // public function changeReports(Request $request)
    // {
    //     $from = $request->from;
    //     $to = $request->to;
    //     if($from)
    //     {
    //     $requests = ChangeRequest::where('created_at', '>=', $from)
    //     ->where('created_at', '<=', $to )->orderBy('id','desc')->get();
    //     }
    //     else
    //     {
    //         $requests = ChangeRequest::orderBy('id','desc')->get();
    //     }
    //     return view('change_reports',
    //     array(
    //         'requests' =>  $requests,
    //         'from' =>  $from,
    //         'to' =>  $to,
    //     ));
    // }
    // public function docReports(Request $request)
    // {
    //     $dco = $request->dco;
    //     $dcos = User::where('role','Document Control Officer')->get();
    //     $requests = ChangeRequest::orderBy('id','desc')->get();
    //     if($dco != null)
    //     {
          
    //         $user = User::where('id',$request->dco)->first();
    //         $requests = ChangeRequest::whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
    //     }
        

    //     return view('dcoReports',
        
    //     array(
    //         'requests' =>  $requests,
    //         'dcos' =>  $dcos,
    //         'dco' =>  $dco,
    //     ));
        
    // }

    // public function delayedRequest(Request $request)
    // {
    //     $departments = Department::where('id',auth()->user()->department_id)->where('status',null)->get();
    //     $companies = Company::where('status',null)->get();
    //     $document_types = DocumentType::get();
    //     $approvers = DepartmentApprover::where('department_id',auth()->user()->department_id)->get();
    //     $requests = ChangeRequest::orderBy('id','desc')
    //         ->when($request->status, function($q)use($request) {
    //             $q->where('status', $request->status);
    //         })
    //         ->get();
    //     // $pre_assessment_approvers = DepartmentDco::where('department_id',auth()->user()->department_id)->get();
    //     $pre_assessment_approvers = DepartmentDco::where('department_id',auth()->user()->department_id)
    //         ->whereHas('user', function($query)use($request) {
    //             $query->where('status', null);
    //         })
    //         ->get();
    //     if(auth()->user()->role == "User")
    //     {
    //         $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
    //     }
    //     else if(auth()->user()->role == "Document Control Officer")
    //     {
    //         $requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)
    //             ->pluck('department_id')->toArray())
    //             ->when($request->status, function($q)use($request) {
    //                 $q->where('status', $request->status);
    //             })
    //             ->orderBy('id','desc')->get();
    //     }
    //     else if(auth()->user()->role == "Department Head")
    //     {
    //         $requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orderBy('id','desc')->get();
    //     }
    //     else if(auth()->user()->role == "Documents and Records Controller")
    //     {
    //         $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
    //     }
    //     return view('delay_request',
        
    //     array(
    //         'requests' =>  $requests,
    //         'pre_assessment_approvers' => $pre_assessment_approvers,
    //         'companies' =>  $companies,
    //         'departments' =>  $departments,
    //         'approvers' =>  $approvers,
    //         'document_types' =>  $document_types,
    //     ));
    // }

    public function comments(Request $request)
    {
        $request->validate([
            'comment' => 'required'
        ]);

        $comments = new Comment;
        $comments->change_request_id = $request->change_request_id;
        $comments->comment = $request->comment;
        $comments->user_id = auth()->user()->id;
        $comments->save();

        $changeRequest = ChangeRequest::with('approvers')->findOrFail($request->change_request_id);

        $recipients = collect([$changeRequest->user_id])
            ->merge($changeRequest->approvers->pluck('user_id'))
            ->unique()
            ->filter(fn($id) => $id != auth()->id());

        foreach ($recipients as $userId) {
            UserNotification::updateOrCreate(
                ['user_id' => $userId, 'type' => 'comment', 'change_request_id' => $request->change_request_id],
                ['read_at' => null]
            );
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function viewChangeRequest($id)
    {
        $changeRequest = ChangeRequest::with('user','comments','approvers','supporting_documents',"history.user")->findOrFail($id);

        return view('change_request.view_change_request',
            array(
                'change_request' => $changeRequest,
            )
        );
    }

    public function confirmPassword(Request $request)
    {
        // dd($request->all());
        $password = auth()->user()->password;

        if (Hash::check($request->password, $password))
        {
            return redirect('documents/signature/'.$request->change_request_id);
        }
        else 
        {
            Alert::warning('The password you provided does not match our records.')->persistent('Dismiss');
            return back();
        }
    }
}



