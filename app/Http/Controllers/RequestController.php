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
use App\SupportingDocument;
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
        $requests = ChangeRequest::get();

        return view('change_requests',
        
        array(
            'requests' =>  $requests,
            // 'pre_assessment_count' => $pre_assessment_count,
            // 'companies' =>  $companies,
            // 'departments' =>  $departments,
            // 'approvers' =>  $approvers,
            // 'document_types' =>  $document_types,
            // 'status' => $request->status,
            // 'declinedCount' => $declinedCount,
            // 'approvedCount' => $approvedCount,
            // 'notDelayedCount' => $notDelayedCount,
            // 'delayedCount' => $delayedCount,
            // 'pre_assessment_approvers' => $pre_assessment_approvers
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
        // dd($request->all());
        $change_request = new ChangeRequest;
        $change_request->title = $request->title;
        $change_request->type = $request->type;
        $change_request->description = $request->description;
        $change_request->category = $request->category;
        $change_request->status = $request->status;
        $change_request->privacy = $request->privacy;
        $change_request->user_id = auth()->user()->id;
        $change_request->revision = 0;
        $change_request->request_status = "Pending";

        $file = $request->file('file');
        $name = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('attachment'),$name);
        $change_request->file = '/attachment/'.$name;

        $change_request->save();

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
        $change_request = ChangeRequest::with('user','comments','approvers.user')->findOrFail($id);

        return view('change_request.view_approval', 
            array(
                'change_request' => $change_request
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
                $new_document->control_code = "DOC-".date('Y', strtotime($changeRequest->created_at)).'-'.str_pad($changeRequest->id,3,'0',STR_PAD_LEFT);
                $new_document->save();

                $changeRequest->document_id = $new_document->id;
                $changeRequest->control_code = $new_document->control_code;
                $changeRequest->revision = 0;
                $changeRequest->status = "Approved";
                $changeRequest->save();

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

                $changeRequest->level = $changeRequest->level+1;
                $changeRequest->save();

                $comment = "<b>Update status: </b><span>".$request->old_status." &#x2192; ".$request->action."</span> <br> Remarks : ".$request->remarks."<br> ";
                $comments = new Comment;
                $comments->change_request_id = $changeRequest->id;
                $comments->comment = $comment;
                $comments->user_id = auth()->user()->id;
                $comments->save();
            }

            Alert::success('Successfully Approved')->persistent('Dismiss');
            return redirect('/for-approval');
        }
        elseif($request->action == "Returned")
        {
            $changeRequest->status = "Pending";
            $changeRequest->level = 1;
            $changeRequest->save(); 

            $comment = "<b>Update status: </b><span>".$request->old_status." &#x2192; ".$request->action."</span> <br> Remarks : ".$request->remarks."<br> ";
            $comments = new Comment;
            $comments->change_request_id = $changeRequest->id;
            $comments->comment = $comment;
            $comments->user_id = auth()->user()->id;
            $comments->save();

            Alert::success('Successfully Returned')->persistent('Dismiss');
            return redirect('/for-approval');
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

    public function comments(Request $request)
    {
        $comments = new Comment;
        $comments->change_request_id = $request->change_request_id;
        $comments->comment = $request->comment;
        $comments->user_id = auth()->user()->id;
        $comments->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
}



