<?php

namespace App\Http\Controllers;
use App\Document;
use App\Department;
use App\Company;
use App\DepartmentApprover;
use App\CopyRequest;
use App\ChangeRequest;
use App\DocumentAttachment;
use App\CopyApprover;
use App\DepartmentDco;
use App\Notifications\NewPreAssessment;
use App\RequestApprover;
use App\ObsoleteAttachment;
use App\Obsolete;
use App\DocumentType;
use App\User;
use App\Notifications\ForApproval;
use App\Notifications\NewPolicy;
use App\Notifications\ApprovedRequest;
use App\Notifications\DeclineRequest;
use App\Notifications\ReturnRequest;
use App\Notifications\PendingRequest;
use App\PreAssessment;
use App\PreAssessmentApprover;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editTile (Request $request,$id)
    {
        $change_request = ChangeRequest::findOrfail($id);
        $change_request->title = $request->title;
        $change_request->type_of_document = $request->document_type;
        $change_request->save();
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function editRequest (Request $request,$id)
    {
        $req = ChangeRequest::findOrfail($id);
        $req->change_request = $request->description;
        $req->indicate_clause = $request->from_clause;
        $req->indicate_changes = $request->to_changes;
        $req->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function test()
    {
          info("START DCO");
        $users = User::where('status',null)->where('role','Document Control Officer')->get();
        foreach($users as $user)
        {
            $change_requests = ChangeRequest::with('approvers')->whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->where('status','Pending')->get();

            $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>Date Requested</th><th>Code</th><th>Approver</th></tr>";
            foreach($change_requests as $request)
            {
                $approver = ($request->approvers)->where('level',$request->level)->first();
                $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
            }
            $table .= "</table>";
            if(count($change_requests) >0)
            {
                $user->notify(new PendingRequest($table));
            }
           
        }
        $users_d = User::where('status',null)->where('role','Business Process Manager')->orWhere('role','Management Representative')->get();
        foreach($users_d as $user)
        {
            $change_requests = ChangeRequest::with('approvers')->where('status','Pending')->get();

            $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>Date Requested</th><th>Code</th><th>Approver</th></tr>";
            foreach($change_requests as $request)
            {
                $approver = ($request->approvers)->where('level',$request->level)->first();
                $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
            }
            $table .= "</table>";
            if(count($change_requests) >0)
            {
                $user->notify(new PendingRequest($table));
            }
        }

        $users_approvers = User::where('status',null)->get();
        foreach($users_approvers as $user)
        {
            $change_requests = ChangeRequest::whereHas('approvers',function($q) use($user){
                $q->where('user_id',  $user->id)->where('status','Pending');
            })->where('status','Pending')->get();

            $copy_requests = CopyRequest::whereHas('approvers',function($q) use($user){
                $q->where('user_id',  $user->id)->where('status','Pending');
            })->where('status','Pending')->get();

            $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th colspan='3'>For Your Approval</th></tr>";
            if(count($change_requests) > 0)
           
            {
                $table .= "<tr><th colspan='3'>Change Requests</th></tr>";
            }
            $table .= "<tr><th>Date Requested</th><th>Code</th><th>Approver</th></tr>";
            foreach($change_requests as $request)
            {
                $approver = ($request->approvers)->where('level',$request->level)->first();
                $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>DICR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
            }
            if(count($copy_requests) > 0)
            {
                $table .= "<tr><th colspan='3'>Copy Requests</th></tr>";
            }
                foreach($copy_requests as $request)
                {
                    $approver = ($request->approvers)->where('level',$request->level)->first();
                    $table .= "<tr><td>".date('Y-m-d',strtotime($request->created_at))."</td><td>CR-".str_pad($request->id, 5, '0', STR_PAD_LEFT)."</td><td>".$approver->user->name."</td></tr>";
                }
        
            
            $table .= "</table>";

            if((count($change_requests) >0) ||(count($copy_requests) >0))
            {
                $user->notify(new PendingRequest($table));
            }
        }
      
        info("END DCO");
    }
    public function index()
    {
        //
       
        $requests = CopyRequest::with('document')->orderBy('id','desc')->get();
        if(auth()->user()->role == "User")
        {
            $requests = CopyRequest::with('document')->where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Document Control Officer")
        {
            $requests = CopyRequest::with('document')->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Department Head")
        {
            $requests = CopyRequest::with('document')->whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = CopyRequest::with('document')->where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        return view('requests',
        array(
            'requests' =>  $requests,
        ));
    }
    public function changeRequests(Request $request)
    {
        $departments = Department::where('id',auth()->user()->department_id)->where('status',null)->get();
        $companies = Company::where('status',null)->get();
        $document_types = DocumentType::get();
        $approvers = DepartmentApprover::where('department_id',auth()->user()->department_id)->get();
        $pre_assessment_approvers = DepartmentDco::where('department_id',auth()->user()->department_id)
            ->whereHas('user', function($query)use($request) {
                $query->where('status', null);
            })
            ->get();
            $requests = ChangeRequest::orderBy('id', 'desc')
            ->when($request->status, function ($q) use ($request) {
                if ($request->status == 'Pending') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                } elseif (in_array($request->status, ['Approved', 'Declined'])) {
                    $q->where('status', $request->status);
                } elseif (in_array($request->status, ['NotDelayed', 'Delayed'])) {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                }
            })
            ->get();
        
        foreach ($requests as $req) {
            $req->target = calculateTargetDate(
                $req->created_at,
                $req->department_head_approved,
                $req->type_of_document
            );
        }
        
        $requests = $requests->filter(function ($req) use ($request) {
            $today = date('Y-m-d'); 
            
            if ($request->status == 'Delayed') {
                return $req->target < $today && $req->status == 'Pending';
            } elseif ($request->status == 'NotDelayed') {
                return $req->target >= $today && $req->status == 'Pending';
            } elseif (in_array($request->status, ['Approved', 'Declined'])) {
                return $req->status == $request->status;
            }
            
            return true;
        });
        
            $requestsCount = ChangeRequest::whereHas('preAssessment', function ($q) {
                $q->where('status', 'Approved');
            })
            ->orWhereDoesntHave('preAssessment')
            ->get();
        
            foreach ($requestsCount as $count) {
                $count->target = calculateTargetDate(
                    $count->created_at,
                    $count->department_head_approved,
                    $count->type_of_document
                );
            }
        
            $declinedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Declined';
            })->count();
        
            $approvedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Approved';
            })->count();
        
            $notDelayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target >= date('Y-m-d');
            })->count();
        
            $delayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target < date('Y-m-d');
            })->count();

        $pre_assessment_count = $requests->filter(function($value, $key) {
            return optional($value->preAssessment)->status == "Pending";
        })->count();
        if (auth()->user()->role == "User") {
            $requestsCount = ChangeRequest::whereHas('preAssessment', function ($q) {
                $q->where('status', 'Approved');
            })
            ->orWhereDoesntHave('preAssessment')
            ->get();
        
            foreach ($requestsCount as $count) {
                $count->target = calculateTargetDate(
                    $count->created_at,
                    $count->department_head_approved,
                    $count->type_of_document
                );
            }
        
            $declinedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Declined' && $request->user_id == auth()->user()->id;
            })->count();
        
            $approvedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Approved' && $request->user_id == auth()->user()->id;
            })->count();
        
            $notDelayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target >= date('Y-m-d') && $request->user_id == auth()->user()->id;
            })->count();
        
            $delayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target < date('Y-m-d') && $request->user_id == auth()->user()->id;
            })->count();
        }         
        else if (auth()->user()->role == "Document Control Officer") {
            $departmentIds = auth()->user()->dco->pluck('department_id')->toArray();
            
            $requestsCount = ChangeRequest::whereIn('department_id', $departmentIds)
                ->where(function ($q) {
                    $q->whereHas('preAssessment', function ($q) {
                        $q->where('status', 'Approved');
                    })
                    ->orWhereDoesntHave('preAssessment');
                })
                ->get();

            foreach ($requestsCount as $count) {
                $count->target = calculateTargetDate(
                    $count->created_at,
                    $count->department_head_approved,
                    $count->type_of_document
                );
            }
        
            $declinedCount = $requestsCount->filter(function ($request) use ($departmentIds) {
                return $request->status == 'Declined' && in_array($request->department_id, $departmentIds);
            })->count();
        
            $approvedCount = $requestsCount->filter(function ($request) use ($departmentIds) {
                return $request->status == 'Approved' && in_array($request->department_id, $departmentIds);
            })->count();
        
            $notDelayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target >= date('Y-m-d');
            })->count();
        
            $delayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target < date('Y-m-d');
            })->count();
        }        
        else if (auth()->user()->role == "Department Head") {
            $departmentIds = auth()->user()->department_head->pluck('id')->toArray();
            
            $requestsCount = ChangeRequest::whereIn('department_id', $departmentIds)
                ->where(function ($q) {
                    $q->whereHas('preAssessment', function ($q) {
                        $q->where('status', 'Approved');
                    })
                    ->orWhereDoesntHave('preAssessment');
                })
                ->get();
        
            foreach ($requestsCount as $count) {
                $count->target = calculateTargetDate(
                    $count->created_at,
                    $count->department_head_approved,
                    $count->type_of_document
                );
            }
        
            $declinedCount = $requestsCount->filter(function ($request) use ($departmentIds) {
                return $request->status == 'Declined' && in_array($request->department_id, $departmentIds);
            })->count();
        
            $approvedCount = $requestsCount->filter(function ($request) use ($departmentIds) {
                return $request->status == 'Approved' && in_array($request->department_id, $departmentIds);
            })->count();
        
            $notDelayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target >= date('Y-m-d');
            })->count();
        
            $delayedCount = $requestsCount->filter(function ($request) {
                return $request->status == 'Pending' && $request->target < date('Y-m-d');
            })->count();
        }
        
        
        if (auth()->user()->role == "User") {
            $requests = ChangeRequest::where('user_id', auth()->user()->id);
            $requests = $requests->when($request->status, function ($q) use ($request) {
                if ($request->status == 'Pending') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                } elseif ($request->status == 'Approved') {
                    $q->where('status', 'Approved');
                } elseif ($request->status == 'Declined') {
                    $q->where('status', 'Declined');
                } elseif ($request->status == 'NotDelayed') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                } elseif ($request->status == 'Delayed') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                }
                })
                ->orderBy('id', 'desc')
                ->get();
            
                foreach ($requests as $req) {
                    $req->target = calculateTargetDate(
                        $req->created_at,
                        $req->department_head_approved,
                        $req->type_of_document
                    );
                }

            $requests = $requests->filter(function ($req) use ($request) {
                $today = now()->format('Y-m-d');
        
                if ($request->status == 'Delayed') {
                    return $req->target < $today && $req->status == 'Pending';
                } elseif ($request->status == 'NotDelayed') {
                    return $req->target >= $today && $req->status == 'Pending';
                } elseif ($request->status == 'Approved') {
                    return $req->status == 'Approved';
                } elseif ($request->status == 'Declined') {
                    return $req->status == 'Declined';
                }
        
                return true; 
            });

            $pre_assessment_count = $requests->filter(function ($value) {
                return optional($value->preAssessment)->status == "Pending";
            })->count();

        } else if (auth()->user()->role == "Document Control Officer") {
            $requests = ChangeRequest::whereIn('department_id', (auth()->user()->dco)->pluck('department_id')->toArray());
    
            $requests = $requests->when($request->status, function ($q) use ($request) {
                if ($request->status == 'Pending') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                } elseif ($request->status == 'Approved') {
                    $q->where('status', 'Approved');
                } elseif ($request->status == 'Declined') {
                    $q->where('status', 'Declined');
                } elseif ($request->status == 'NotDelayed') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                } elseif ($request->status == 'Delayed') {
                    $q->where('status', 'Pending')
                        ->where(function ($q) {
                            $q->whereHas('preAssessment', function ($q) {
                                $q->where('status', 'Approved');
                            })->orWhereDoesntHave('preAssessment');
                        });
                }
            })
            ->orderBy('id', 'desc')
            ->get();
        
            foreach ($requests as $req) {
                $req->target = calculateTargetDate(
                    $req->created_at,
                    $req->department_head_approved,
                    $req->type_of_document
                );
            }
        
            $requests = $requests->filter(function ($req) use ($request) {
                $today = now()->format('Y-m-d');
        
                if ($request->status == 'Delayed') {
                    return $req->target < $today && $req->status == 'Pending';
                } elseif ($request->status == 'NotDelayed') {
                    return $req->target >= $today && $req->status == 'Pending';
                } elseif ($request->status == 'Approved') {
                    return $req->status == 'Approved';
                } elseif ($request->status == 'Declined') {
                    return $req->status == 'Declined';
                }
        
                return true; 
            });
        
            $pre_assessment_count = $requests->filter(function ($value) {
                return optional($value->preAssessment)->status == "Pending";
            })->count();
        }
        
        else if (auth()->user()->role == "Department Head") {
            $requests = ChangeRequest::whereIn('department_id', (auth()->user()->department_head)->pluck('id')->toArray());

                 $requests = $requests->when($request->status, function ($q) use ($request) {
                    if ($request->status == 'Pending') {
                        $q->where('status', 'Pending')
                            ->where(function ($q) {
                                $q->whereHas('preAssessment', function ($q) {
                                    $q->where('status', 'Approved');
                                })->orWhereDoesntHave('preAssessment');
                            });
                    } elseif ($request->status == 'Approved') {
                        $q->where('status', 'Approved');
                    } elseif ($request->status == 'Declined') {
                        $q->where('status', 'Declined');
                    } elseif ($request->status == 'NotDelayed') {
                        $q->where('status', 'Pending')
                            ->where(function ($q) {
                                $q->whereHas('preAssessment', function ($q) {
                                    $q->where('status', 'Approved');
                                })->orWhereDoesntHave('preAssessment');
                            });
                    } elseif ($request->status == 'Delayed') {
                        $q->where('status', 'Pending')
                            ->where(function ($q) {
                                $q->whereHas('preAssessment', function ($q) {
                                    $q->where('status', 'Approved');
                                })->orWhereDoesntHave('preAssessment');
                            });
                    }
                })
                ->orderBy('id', 'desc')
                ->get();
        
            foreach ($requests as $req) {
                $req->target = calculateTargetDate(
                    $req->created_at,
                    $req->department_head_approved,
                    $req->type_of_document
                );
            }
        
            $requests = $requests->filter(function ($req) use ($request) {
                $today = now()->format('Y-m-d');
        
                if ($request->status == 'Delayed') {
                    return $req->target < $today && $req->status == 'Pending';
                } elseif ($request->status == 'NotDelayed') {
                    return $req->target >= $today && $req->status == 'Pending';
                } elseif ($request->status == 'Approved') {
                    return $req->status == 'Approved';
                } elseif ($request->status == 'Declined') {
                    return $req->status == 'Declined';
                }
        
                return true; 
            });
            $pre_assessment_count = $requests->filter(function ($value) {
                return optional($value->preAssessment)->status == "Pending";
            })->count();
        }
        
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)
                ->when($request->status, function($q)use($request) {
                    $q->where('status', $request->status);
                })
                
                ->orderBy('id','desc')
                ->get();
        }
        return view('change_requests',
        
        array(
            'requests' =>  $requests,
            'pre_assessment_count' => $pre_assessment_count,
            'companies' =>  $companies,
            'departments' =>  $departments,
            'approvers' =>  $approvers,
            'document_types' =>  $document_types,
            'status' => $request->status,
            'declinedCount' => $declinedCount,
            'approvedCount' => $approvedCount,
            'notDelayedCount' => $notDelayedCount,
            'delayedCount' => $delayedCount,
            'pre_assessment_approvers' => $pre_assessment_approvers
        ));
    }
    public function removeApprover()
    {
        //
       
        $change_for_approvals = RequestApprover::orderBy('id','desc')->get();
       

        return view('for_removals',
        array(
           'change_for_approvals' => $change_for_approvals,
        ));
    }
    public function removeApp(Request $request,$id)
    {
        if($request->approver == null)
        {
            $appro = [];
        }
        else
        {
            $appro = $request->approver;
        }
        $approvers = RequestApprover::orderBy('id','desc')->where('change_request_id',$id)->whereNotIn('id', $appro)->where('status','Waiting')->delete();
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
        
    }
    public function forApproval()
    {
        //
        $document_types = DocumentType::get();
        $copy_for_approvals = CopyApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->get();
        $change_for_approvals = RequestApprover::orderBy('id','desc')->where('user_id',auth()->user()->id)->get();
        if(auth()->user()->role == "Administrator")
        {
            $copy_for_approvals = CopyApprover::orderBy('id','desc')->get();
            $change_for_approvals = RequestApprover::orderBy('id','desc')->get();
        }
       

        return view('for_approval',
        array(
           'copy_for_approvals' => $copy_for_approvals,
           'change_for_approvals' => $change_for_approvals,
           'document_types' => $document_types,
        ));
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
        //
        $request->validate([
            'supporting_document' => 'mimes:pdf',
            // 'reason_for_new_request' => 'required'
        ]);
        
        $document = Document::findOrfail($request->id);
        // $document->process_owner = auth()->user()->id;
        // $document->save();

        $original_pdf = DocumentAttachment::where('document_id',$request->id)->where('type','pdf_copy')->first();
        $original_soft_copy = DocumentAttachment::where('document_id',$request->id)->where('type','soft_copy')->first();

        $preAssessment = new PreAssessment;
        $preAssessment->control_code = $request->control_code;
        $preAssessment->title = $request->title;
        $preAssessment->revision = $request->revision;
        $preAssessment->type_of_document = $document->category;
        $preAssessment->request_type = $request->request_type;
        $preAssessment->effective_date = $request->effective_date;
        $preAssessment->link_draft = $request->draft_link;
        $preAssessment->reason_for_changes = $request->reason_for_new_request;
        $preAssessment->change_request = $request->description;
        $preAssessment->indicate_clause = $request->from_clause;
        $preAssessment->indicate_changes = $request->to_changes;
        $preAssessment->department_id = auth()->user()->department_id;
        $preAssessment->company_id = auth()->user()->company_id;
        $preAssessment->user_id = auth()->user()->id;
        $preAssessment->status = "Pending";
        if($request->has('supporting_document'))
        {
            $attachment = $request->file('supporting_document');
            $name = time(). '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
            $file_name = '/pre_assessment_attachments/' . $name;

            $preAssessment->supporting_documents = $file_name;
        }
        if ($original_pdf != null)
        {
            $preAssessment->original_attachment_pdf = $original_pdf->attachment;
        }
        if ($original_soft_copy != null)
        {
            $preAssessment->original_attachment_soft_copy = $original_soft_copy->attachment;
        }

        $preAssessment->save();

        $changeRequest = new ChangeRequest;
        $changeRequest->pre_assessment_id = $preAssessment->id;
        $changeRequest->request_type = $request->request_type;
        $changeRequest->effective_date = $request->effective_date;
        $changeRequest->department_id = auth()->user()->department_id;
        $changeRequest->company_id = auth()->user()->company_id;
        $changeRequest->user_id = auth()->user()->id;
        $changeRequest->type_of_document = $document->category;
        $changeRequest->document_id = $request->id;
        $changeRequest->reason_for_changes = $request->reason_for_new_request;
        $changeRequest->change_request = $request->description;
        $changeRequest->indicate_clause = $request->from_clause;
        $changeRequest->indicate_changes = $request->to_changes;
        $changeRequest->link_draft = $request->draft_link;
        $changeRequest->status = "Pending";
        $changeRequest->level = 1;
        $changeRequest->control_code = $request->control_code;
        $changeRequest->title = $request->title;
        $changeRequest->revision = $request->revision;
        if($original_pdf != null)
        {
            $changeRequest->original_attachment_pdf = $original_pdf->attachment;
        }
        if($original_soft_copy != null)
        {
            $changeRequest->original_attachment_soft_copy = $original_soft_copy->attachment;
        }
        if ($request->has('supporting_document'))
        {
            // $attachment = $request->file('supporting_document');
            // $name = time() . '_' . $attachment->getClientOriginalName();
            // $attachment->move(public_path().'/document_attachment/', $name);
            // $file_name = '/document_attachment/' . $name;

            // $changeRequest->supporting_documents = $file_name;
            $changeRequest->supporting_documents = $preAssessment->supporting_documents;
        }

        $changeRequest->save();

        $user = User::where('role', 'Document Control Officer')->where('status', null)->pluck('id')->toArray();
        $dco = DepartmentDco::where('department_id', auth()->user()->department_id)->whereIn('user_id', $user)->first();

        if ($dco != null)
        {
            $preAssessmentApprover = new PreAssessmentApprover;
            $preAssessmentApprover->pre_assessment_id = $preAssessment->id;
            $preAssessmentApprover->user_id = $dco->user_id;
            $preAssessmentApprover->status = "Pending";
            $preAssessmentApprover->start_date = date('Y-m-d');
            $preAssessmentApprover->save();

            $approvedRequestsNotif = User::where('id',$dco->user_id)->first();
            
            $approvedRequestsNotif->notify(new NewPreAssessment($preAssessment, "Pre-Assessment Approval"));
        }
    
        // $approvers = DepartmentApprover::where('department_id',$document->department_id)->orderBy('level','asc')->get();
        // foreach($approvers as $approver)
        // {
        //     $copy_approver = new RequestApprover;
        //     $copy_approver->change_request_id = $changeRequest->id;
        //     $copy_approver->user_id = $approver->user_id;
           
        //     if($approver->level == 1)
        //     {
        //         $copy_approver->status = "Pending";
        //         $copy_approver->start_date = date('Y-m-d');
        //         $ApproverNotif = User::where('id',$copy_approver->user_id)->first();
        //         $ApproverNotif->notify(new ForApproval($changeRequest,"DICR-","Change Request"));
        //     }
        //     else
        //     {
        //         $copy_approver->status = "Waiting";
               
        //     }
        //     $copy_approver->level = $approver->level;
        //     $copy_approver->save();
        // }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return redirect('/change-requests');

    }
    public function new_request(Request $request)
    {
        //
        // dd($request->all());
        $request->validate([
            'supporting_document' => 'mimes:pdf',
            // 'category' => 'required',
            // 'reason_for_new_request' => 'required'
        ]);

        $preAssessment = new PreAssessment;
        $preAssessment->request_type = $request->request_type;
        $preAssessment->effective_date = $request->effective_date;
        $preAssessment->department_id = $request->department;
        $preAssessment->user_id = auth()->user()->id;
        $preAssessment->type_of_document = $request->category;
        $preAssessment->reason_for_changes = $request->reason_for_new_request;
        $preAssessment->change_request = $request->description;
        $preAssessment->supporting_documents = $request->supporting_document;
        $preAssessment->link_draft = $request->draft_link;
        $preAssessment->title = $request->title;
        $preAssessment->company_id = $request->company;
        $preAssessment->status = "Pending";

        if($request->has('soft_copy'))
        {
            $attachment = $request->file('soft_copy');
        
            $name = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
            $file_name = '/pre_assessment_attachments/' . $name;
            $preAssessment->soft_copy = $file_name;
        }
        if($request->has('pdf_copy'))
        {
            $attachment = $request->file('pdf_copy');
            $name = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
            $file_name = '/pre_assessment_attachments/' . $name;
            $preAssessment->pdf_copy = $file_name;
        }
        if($request->has('fillable_copy'))
        {
            $attachment = $request->file('fillable_copy');
            $name = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
            $file_name = '/pre_assessment_attachments/' . $name;
            $preAssessment->fillable_copy = $file_name;
        }
        if($request->has('supporting_document'))
        {
            $attachment = $request->file('supporting_document');
            $name = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/pre_assessment_attachments/', $name);
            $file_name = '/pre_assessment_attachments/' . $name;
            $preAssessment->supporting_documents = $file_name;
        }

        $preAssessment->save();

        $changeRequest = new ChangeRequest;
        $changeRequest->request_type = $request->request_type;
        $changeRequest->effective_date = $request->effective_date;
        $changeRequest->department_id = $request->department;
        $changeRequest->company_id = $request->company;
        $changeRequest->title = $request->title;
        $changeRequest->user_id = auth()->user()->id;
        $changeRequest->type_of_document = $request->category;
        $changeRequest->change_request = $request->description;
        $changeRequest->link_draft = $request->draft_link;
        $changeRequest->status = "Pending";
        $changeRequest->level = 1;
        $changeRequest->reason_for_changes = $request->reason_for_new_request;
        $changeRequest->pre_assessment_id = $preAssessment->id;
        if($request->has('soft_copy'))
        {
            $changeRequest->soft_copy = $preAssessment->soft_copy;
        }
        if($request->has('pdf_copy'))
        {
            $changeRequest->pdf_copy = $preAssessment->pdf_copy;
        }
        if($request->has('fillable_copy'))
        {
            $changeRequest->fillable_copy = $preAssessment->fillable_copy;
        }
        if($request->has('supporting_document'))
        {
            $changeRequest->supporting_documents = $preAssessment->supporting_documents ;
        }
        
        $changeRequest->save();

        $user = User::where('role', 'Document Control Officer')->where('status', null)->pluck('id')->toArray();
        $dco = DepartmentDco::where('department_id', auth()->user()->department_id)->whereIn('user_id', $user)->first();

        if ($dco != null)
        {
            $preAssessmentApprover = new PreAssessmentApprover;
            $preAssessmentApprover->pre_assessment_id = $preAssessment->id;
            $preAssessmentApprover->user_id = $dco->user_id;
            $preAssessmentApprover->status = "Pending";
            $preAssessmentApprover->start_date = date('Y-m-d');
            $preAssessmentApprover->save();

            $approvedRequestsNotif = User::where('id',$dco->user_id)->first();

            $approvedRequestsNotif->notify(new NewPreAssessment($preAssessment, "Pre-Assessment Approval"));
        }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return redirect('/change-requests');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $copyRequestApprover = RequestApprover::findOrfail($id);
        $copyRequestApprover->status = $request->action;
        $copyRequestApprover->remarks = $request->remarks;
        $copyRequestApprover->save();

        $copyApprover = RequestApprover::where('change_request_id',$copyRequestApprover->change_request_id)
        ->where(function ($query) {
            $query->where('status', 'Waiting')
                  ->orWhere('status', 'Returned');
        })
        ->orderBy('level','asc')->first();
        $copyRequest = ChangeRequest::findOrfail($copyRequestApprover->change_request_id);

        
        if($request->action == "Approved")
        {
            if(auth()->user()->role == "Document Control Officer")
            {
                if($request->has('soft_copy'))
                {
                    $attachment = $request->file('soft_copy');
                
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path() . '/document_attachments/', $name);
                    $file_name = '/document_attachments/' . $name;
                    $copyRequest->soft_copy = $file_name;
                    $copyRequest->save();
                }
                if($request->has('pdf_copy'))
                {
                    $attachment = $request->file('pdf_copy');
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path() . '/document_attachments/', $name);
                    $file_name = '/document_attachments/' . $name;
                    $copyRequest->pdf_copy = $file_name;
                    $copyRequest->save();
                }
                if($request->has('fillable_copy'))
                {
                    $attachment = $request->file('fillable_copy');
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path() . '/document_attachments/', $name);
                    $file_name = '/document_attachments/' . $name;
                    $copyRequest->fillable_copy = $file_name;
                    $copyRequest->save();
                }
                
            }
            elseif((auth()->user()->role == "Department Head") || auth()->user()->role == "Business Process Manager")
            {
                    $copyRequest->department_head_approved = Carbon::now();
                    $copyRequest->save();            
            }
            if($copyApprover == null)
            {
                if($copyRequest->request_type == "Revision")
                {
                    $document = Document::findOrfail($copyRequest->document_id);
                    $obsolete = new Obsolete;
                    $obsolete->document_id = $document->id;
                    $obsolete->control_code = $document->control_code;
                    $obsolete->company_id = $document->company_id;
                    $obsolete->department_id = $document->department_id;
                    $obsolete->title = $document->title;
                    $obsolete->category = $document->category;
                    $obsolete->other_category = $document->other_category;
                    $obsolete->user_id = $copyRequest->user_id;
                    $obsolete->version = $document->version;
                    $obsolete->save();

                    $attacments = DocumentAttachment::where('document_id',$document->id)->get();
                    foreach($attacments as $attach)
                    {
                        $obsolete_attach = new ObsoleteAttachment;
                        $obsolete_attach->obsolete_id = $obsolete->id;
                        $obsolete_attach->attachment = $attach->attachment;
                        $obsolete_attach->type = $attach->type;
                        $obsolete_attach->save();
                    }

                    $document->version = $document->version + 1;
                    $document->effective_date = $copyRequest->effective_date;
                    $document->user_id = $copyRequest->user_id;
                    $document->save();

 
                    $attac = DocumentAttachment::where('document_id',$document->id)->delete();
                    $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $document->id; 
                    $new_attach->type = "soft_copy"; 
                    $new_attach->attachment = $copyRequest->soft_copy; 
                    $new_attach->save(); 

                    $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $document->id; 
                    $new_attach->type = "pdf_copy"; 
                    $new_attach->attachment = $copyRequest->pdf_copy; 
                    $new_attach->save(); 

                    if($copyRequest->fillable_copy != null)
                    {
                        $new_attach = new DocumentAttachment;
                        $new_attach->document_id = $document->id; 
                        $new_attach->type = "fillable_copy"; 
                        $new_attach->attachment = $copyRequest->fillable_copy; 
                        $new_attach->save(); 
                    }

                }
                if($copyRequest->request_type == "Obsolete")
                {
                    $document = Document::findOrfail($copyRequest->document_id);
                    $document->status = "Obsolete";
                    $document->save();
                }
                if($copyRequest->request_type == "New")
                {
                   $company = Company::findOrFail($copyRequest->company_id);
                   $department = Department::findOrFail($copyRequest->department_id);
                   $type_of_doc = DocumentType::where('name',$copyRequest->type_of_document)->first();
                   $company_code = explode('-',$department->code);
                   
                   $document_get_latest = Document::where('company_id',$copyRequest->company_id)->where('department_id',$copyRequest->department_id)->where('category',$copyRequest->type_of_document)->orderBy('control_code','desc')->first();
                   if($document_get_latest == null)
                   {
                        $code = $company_code[0]."-".$type_of_doc->code."-".$company_code[1]."-001";
                   }
                   else
                   {
                        $c = $document_get_latest->control_code;
                        $c = explode("-", $c);
                        $last_code = ((int)$c[count($c)-1])+1;
                        $code = $company_code[0]."-".$type_of_doc->code."-".$company_code[1]."-".str_pad($last_code, 3, '0', STR_PAD_LEFT);
                   }
                   $new_document = new Document;
                   $new_document->company_id = $copyRequest->company_id;
                   $new_document->department_id = $copyRequest->department_id;
                   $new_document->title = $copyRequest->title;
                   $new_document->category = $copyRequest->type_of_document;
                   $new_document->effective_date = date('Y-m-d');
                   $new_document->user_id = $copyRequest->user_id;
                   $new_document->version = 0;
                   $new_document->control_code = $code;
                   $new_document->save();

                   $copyRequest->document_id = $new_document->id;
                   $copyRequest->control_code = $code;
                   $copyRequest->revision = 0;

                   $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $new_document->id; 
                    $new_attach->type = "soft_copy"; 
                    $new_attach->attachment = $copyRequest->soft_copy; 
                    $new_attach->save(); 

                    $new_attach = new DocumentAttachment;
                    $new_attach->document_id = $new_document->id; 
                    $new_attach->type = "pdf_copy"; 
                    $new_attach->attachment = $copyRequest->pdf_copy; 
                    $new_attach->save(); 

                    if($copyRequest->fillable_copy != null)
                    {
                        $new_attach = new DocumentAttachment;
                        $new_attach->document_id = $new_document->id; 
                        $new_attach->type = "fillable_copy"; 
                        $new_attach->attachment = $copyRequest->fillable_copy; 
                        $new_attach->save(); 
                    }
                }
                $copyRequest->status = "Approved";
                $copyRequest->save();

                $approvedRequestsNotif = User::where('id',$copyRequest->user_id)->first();
                $approvedRequestsNotif->notify(new ApprovedRequest($copyRequest,"DICR-","Document Information Change Request","request"));

                $approvers_all = RequestApprover::where('change_request_id',$copyRequestApprover->change_request_id)->orderBy('level','asc')->get();
                foreach($approvers_all as $user_approver)
                {
                    $app = User::where('id',$user_approver->user_id)->first();
                    $app->notify(new NewPolicy($copyRequest,"DICR-","Document Information Change Request","request"));
                }
            }
            else
            {
                $copyApprover->start_date = date('Y-m-d');
                $copyApprover->status = "Pending";
                $copyApprover->save();
                $copyRequest->level = $copyRequest->level+1;
                $copyRequest->save();

                $nextApproverNotif = User::where('id',$copyApprover->user_id)->first();
                $nextApproverNotif->notify(new ForApproval($copyRequest,"DICR-","Document Information Change Request"));
            }

            if ($copyRequest->status == "Approved")
            {   
                $document = Document::findOrFail($copyRequest->document_id);
                $document->process_owner = $copyRequest->user_id;
                $document->title = $copyRequest->title;
                $document->save();
            }

            Alert::success('Successfully Approved')->persistent('Dismiss');
            return back();
        }
        elseif($request->action == "Returned")
        {
            // dd($request->all());
            $returnTo = $request->input('return_to');
            $copyRequest->status = "Pending";
            $copyRequest->level =1;
            $copyRequest->save(); 
            $copyApprovers = RequestApprover::where('change_request_id', $copyRequestApprover->change_request_id)
            ->orderBy('level', 'asc')
            ->get();

            if ($returnTo == 'DepartmentHead')
            {
                $approvers = $copyApprovers->pluck('user_id')->toArray();
                $dept_head = User::whereIn('id', $approvers)
                    ->where(function($q) {
                        $q->where('role','Department Head')->orWhere('role', 'Business Process Manager');
                    })
                    ->where('department_id', $request->department)
                    ->first();
                
                foreach($copyApprovers as $approver)
                {
                    if ($dept_head->id == $approver->user_id)
                    {
                        $approver->status = 'Pending';
                    }
                    else
                    {
                        if ($approver->level > 1)
                        {
                            $approver->status = 'Waiting';
                        }
                    }

                    if ($approver->user_id == auth()->id())
                    {
                        $approver->status = 'Returned';
                    }

                    $approver->save();
                }
            }
            elseif($returnTo == 'DocumentControlOfficer')
            {
                $approvers = $copyApprovers->pluck('user_id')->toArray();
                $dco = User::whereIn('id', $approvers)->where('role', 'Document Control Officer')->first();
                
                foreach($copyApprovers as $approver)
                {
                    if ($dco->id == $approver->user_id)
                    {
                        $approver->status = 'Pending';
                    }
                    else
                    {
                        if ($approver->level > 1)
                        {
                            $approver->status = 'Waiting';
                        }
                    }

                    if ($approver->user_id == auth()->id())
                    {
                        $approver->status = 'Returned';
                    }

                    $approver->save();
                }
            }
            
            // if ($returnTo == 'DepartmentHead') {
            //     $approver = $copyApprovers
            //     ->filter(function ($approver) {
            //         return $approver->level == 1;
            //     })
            //     ->pluck('user_id')->toArray();
            // } elseif ($returnTo == 'DocumentControlOfficer') {
            //     $approver = $copyApprovers
            //     ->filter(function ($approver) {
            //         return $approver->level == 2;
            //     })
            //     ->pluck('user_id')->toArray();
            // }
            // dd($approver, $copyApprovers);
           
            // $userHead = User::whereIn('id', $approver)
            // ->where(function ($query) {
            //     $query->where('role', 'Business Process Manager')
            //         ->orWhere('role', 'Department Head');
            // })
            // // ->where('department_id', $copyRequest->department_id)
            // ->first();
            // // dd($userHead);

            // $dco = User::wherein('id', $approver)->where('role', 'Document Control Officer')->first();
            
            // $dcoLevel = $dco ? $copyApprovers->where('user_id', $dco->id)->first()->level : null;
            // $deptHeadLevel = $userHead ? $copyApprovers->where('user_id', $userHead->id)->first()->level : null;

            // foreach ($copyApprovers as $approver) {
            //     if ($returnTo == 'DepartmentHead') {
            //         if ($approver->level > $deptHeadLevel) {
            //             $approver->status = 'Waiting';
            //         } elseif ($approver->user_id == $userHead->id) {
            //             $approver->status = 'Pending';
            //         }
            //     }
            //     elseif ($returnTo == 'DocumentControlOfficer') {
            //          if ($approver->level > $dcoLevel) {
            //             $approver->status = 'Waiting';
            //         } elseif ($approver->user_id == $dco->id) {
            //             $approver->status = 'Pending';
            //         }
            //     }
            //     $approver->save(); 
            //     }
            $declinedRequestNotif = User::where('id',$copyRequest->user_id)->first();
            $declinedRequestNotif->notify(new ReturnRequest($copyRequest,"DICR-","Document Information Change Request","change-requests"));

            Alert::success('Successfully Returned')->persistent('Dismiss');
            return back();
        }
        else
        {
            $copyRequest->status = "Declined";
            $copyRequest->save(); 
            
            $declinedRequestNotif = User::where('id',$copyRequest->user_id)->first();
            $declinedRequestNotif->notify(new DeclineRequest($copyRequest,"DICR-","Document Information Change Request","change-requests"));

            Alert::success('Successfully Declined')->persistent('Dismiss');
            return back();
        }
    
    }
    public function changeReports(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        if($from)
        {
        $requests = ChangeRequest::where('created_at', '>=', $from)
        ->where('created_at', '<=', $to )->orderBy('id','desc')->get();
        }
        else
        {
            $requests = ChangeRequest::orderBy('id','desc')->get();
        }
        return view('change_reports',
        array(
            'requests' =>  $requests,
            'from' =>  $from,
            'to' =>  $to,
        ));
    }
    public function docReports(Request $request)
    {
        $dco = $request->dco;
        $dcos = User::where('role','Document Control Officer')->get();
        $requests = ChangeRequest::orderBy('id','desc')->get();
        if($dco != null)
        {
          
            $user = User::where('id',$request->dco)->first();
            $requests = ChangeRequest::whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->orderBy('id','desc')->get();
        }
        

        return view('dcoReports',
        
        array(
            'requests' =>  $requests,
            'dcos' =>  $dcos,
            'dco' =>  $dco,
        ));
        
    }

    public function delayedRequest(Request $request)
    {
        $departments = Department::where('id',auth()->user()->department_id)->where('status',null)->get();
        $companies = Company::where('status',null)->get();
        $document_types = DocumentType::get();
        $approvers = DepartmentApprover::where('department_id',auth()->user()->department_id)->get();
        $requests = ChangeRequest::orderBy('id','desc')
            ->when($request->status, function($q)use($request) {
                $q->where('status', $request->status);
            })
            ->get();
        // $pre_assessment_approvers = DepartmentDco::where('department_id',auth()->user()->department_id)->get();
        $pre_assessment_approvers = DepartmentDco::where('department_id',auth()->user()->department_id)
            ->whereHas('user', function($query)use($request) {
                $query->where('status', null);
            })
            ->get();
        if(auth()->user()->role == "User")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Document Control Officer")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)
                ->pluck('department_id')->toArray())
                ->when($request->status, function($q)use($request) {
                    $q->where('status', $request->status);
                })
                ->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Department Head")
        {
            $requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orderBy('id','desc')->get();
        }
        else if(auth()->user()->role == "Documents and Records Controller")
        {
            $requests = ChangeRequest::where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
        }
        return view('delay_request',
        
        array(
            'requests' =>  $requests,
            'pre_assessment_approvers' => $pre_assessment_approvers,
            'companies' =>  $companies,
            'departments' =>  $departments,
            'approvers' =>  $approvers,
            'document_types' =>  $document_types,
        ));
    }
}



