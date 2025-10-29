<?php

namespace App\Http\Controllers;
use App\CopyRequest;
use App\CopyApprover;
use App\Document;
use App\User;
use App\DocumentAccess;
use App\DocumentAttachment;
use Illuminate\Http\Request;
use App\Notifications\ForApproval;
use App\Notifications\ApprovedRequest;
use App\Notifications\DeclineRequest;

use RealRashid\SweetAlert\Facades\Alert;
class CopyController extends Controller
{
    //
    public function store(Request $request)
    {
        $document = Document::findOrFail($request->id);
        // $document->process_owner = auth()->user()->id;
        // $document->save();

        $copy_request = new CopyRequest;
        $copy_request->type_of_document = $request->type_of_document;
        $copy_request->purpose = $request->purpose;
        $copy_request->document_id = $request->id;
        $copy_request->control_code = $request->control_code;
        $copy_request->title = $request->title;
        $copy_request->revision = $request->revision;
        $copy_request->user_id = auth()->user()->id;
        $copy_request->copy_count = $request->name;
        $copy_request->date_needed = $request->date_needed;
        $copy_request->department_id = auth()->user()->department_id;
        $copy_request->company_id = auth()->user()->company_id;
        $copy_request->status = "Pending";
        $copy_request->level = 1;
        $copy_request->save();

        if ($request->immediate_head != null) {
            $copy_approver = new CopyApprover;
            $copy_approver->copy_request_id = $copy_request->id;
            $copy_approver->user_id = $request->immediate_head;
            $copy_approver->status = "Pending";
            $copy_approver->start_date = date('Y-m-d');
            $copy_approver->level = 1;
            $copy_approver->save();
        }
    
        if ($request->immediate_head_document != null && $request->immediate_head != $request->immediate_head_document) {
            $copy_approver = new CopyApprover;
            $copy_approver->copy_request_id = $copy_request->id;
            $copy_approver->user_id = $request->immediate_head_document;
            $copy_approver->status = "Waiting";
            $copy_approver->level = 2;
            $copy_approver->save();
        }
        // $copy_request->level = 1;
        // if($request->immediate_head == auth()->user()->id)
        // {
        //     $copy_request->level = 2; 
        // }
        
        // $copy_request->save();
        // if($request->immediate_head != auth()->user()->id)
        // {
        //     $copy_approver = new CopyApprover;
        //     $copy_approver->copy_request_id = $copy_request->id;
        //     $copy_approver->user_id = $request->immediate_head;
        //     $copy_approver->status = "Pending";
        //     $copy_approver->start_date = date('Y-m-d');
        //     $copy_approver->level = 1;
        //     $copy_approver->save();
        // }
        
        if($request->immediate_head != auth()->user()->id)
        {
            $first_notify = User::where('id',$request->immediate_head)->first();
        }
        else
        {
            $first_notify = User::where('id',$request->immediate_head_document)->first();
        }
        $first_notify->notify(new ForApproval($copy_request,"CR-","Copy Request"));
        $dco = User::whereIn('id', $request->dco)->get();
        foreach($dco as $d)
        {
            $d->notify(new ForApproval($copy_request,"CR-","Copy Request"));
        }

        // $copy_approver = new CopyApprover;
        // $copy_approver->copy_request_id = $copy_request->id;
        // $copy_approver->user_id = $request->drc;
        // $copy_approver->status = "Waiting";
        // if($request->immediate_head == auth()->user()->id)
        // {
        // $copy_approver->status = "Pending";
        // }
        // $copy_approver->level = 2;
        // $copy_approver->save();

        // $copy_approver = new CopyApprover;
        // $copy_approver->copy_request_id = $copy_request->id;
        // $copy_approver->user_id = $request->drc_head;
        // $copy_approver->status = "Waiting";
        // $copy_approver->level = 3;
        // $copy_approver->save();
        
        Alert::success('Successfully Requested')->persistent('Dismiss');
        return redirect('/request');
    }

    public function action(Request $request,$id)
    {

        $copyRequestApprover = CopyApprover::findOrfail($id);
        $copyRequestApprover->status = $request->action;
        $copyRequestApprover->remarks = $request->remarks;
        $copyRequestApprover->save();

        $copyApprover = CopyApprover::where('copy_request_id',$copyRequestApprover->copy_request_id)->where('status','Waiting')->orderBy('level','asc')->first();
        $copyRequest = CopyRequest::findOrfail($copyRequestApprover->copy_request_id);

        if($request->action == "Approved")
        {
            // $document = Document::findOrFail($request->id);
            // $document->process_owner = $request->user_id;
            // $document->save();

            if($copyApprover == null)
            {
                
                $copyRequest->status = "Approved";
                $copyRequest->expiration_date = date('Y-m-d',strtotime("+7 day"));
                $copyRequest->save();
                if($copyRequest->type_of_document == "E-Copy")
                {
                    $copyRequestDocument = DocumentAttachment::where('document_id',$copyRequest->document_id)->where('type','pdf_copy')->first();
                    $access = new DocumentAccess;
                    $access->attachment_id = $copyRequestDocument->id;
                    $access->user_id = $copyRequest->user_id;
                    $access->expiration_date = date('Y-m-d',strtotime("+7 day"));
                    $access->copy_request_id = $copyRequest->id;
                    $access->save();
                }
                $approvedRequestsNotif = User::where('id',$copyRequest->user_id)->first();
                $approvedRequestsNotif->notify(new ApprovedRequest($copyRequest,"CR-","Copy Request","request"));
                
            }
            else
            {
        
                $copyApprover->start_date = date('Y-m-d');
                $copyApprover->status = "Pending";
                $copyApprover->save();
                $copyRequest->level = $copyRequest->level+1;
                $copyRequest->save();

                $nextNotify = User::where('id',$copyApprover->user_id)->first();
                $nextNotify->notify(new ForApproval($copyRequest,"CR-","Copy Request"));
                
            }
            Alert::success('Successfully Approved')->persistent('Dismiss');
            return back();
        }
        else
        {
            $copyRequest->status = "Declined";
            $copyRequest->save(); 
            Alert::success('Successfully Declined')->persistent('Dismiss');

            $declinedRequestNotif = User::where('id',$copyRequest->user_id)->first();
            $declinedRequestNotif->notify(new DeclineRequest($copyRequest,"CR-","Copy Request","request"));

            return back();
        }

       
        
    }

    public function copyReports(Request $request)
    {
        $search = $request->yearmonth;
        if($search)
        {
        $year = date('Y',strtotime($search."-01"));
        $month = date('m',strtotime($search."-01"));
        $requests = CopyRequest::whereYear('created_at', '=', $year)
        ->whereMonth('created_at', '=', $month)->orderBy('id','desc')->get();
        }
        else
        {
            $requests = CopyRequest::orderBy('id','desc')->get();
        }
        return view('copy_reports',
        array(
            'requests' =>  $requests,
            'search' =>  $search,
        ));
    }
}
