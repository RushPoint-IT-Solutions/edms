<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\Company;
use App\Department;
use App\DepartmentApprover;
use App\DepartmentDco;
use App\DocumentType;
use App\Notifications\DeclinePreAssessmentNotification;
use App\PreAssessment;
use App\PreAssessmentApprover;
use App\RequestApprover;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PreAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = Department::where('id',auth()->user()->department_id)->where('status',null)->get();
        $companies = Company::where('status',null)->get();
        $document_types = DocumentType::get();
        $approver = DepartmentDco::where('department_id',auth()->user()->department_id)->first();
        $loggedInUserId = auth()->user()->id;

        if (auth()->user()->role == "Document Control Officer") {
            $pendingCount = PreAssessment::where('status', 'Pending')
                ->whereHas('approvers', function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId);
                })
                ->count();
        
            $declinedCount = PreAssessment::where('status', 'Declined')
                ->whereHas('approvers', function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId);
                })
                ->count();
        
            $approvedCount = PreAssessment::where('status', 'Approved')
                ->whereHas('approvers', function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId);
                })
                ->count();
        
            $notDelayedCount = PreAssessment::where('status', 'Pending')
                ->whereHas('approvers', function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId);
                })
                ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) > CURDATE()")
                ->count();
        
            $delayedCount = PreAssessment::where('status', 'Pending')
                ->whereHas('approvers', function ($query) use ($loggedInUserId) {
                    $query->where('user_id', $loggedInUserId);
                })
                ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) < CURDATE()")
                ->count();
        } else {
            $pendingCount = PreAssessment::where('status', 'Pending')->count();
            $declinedCount = PreAssessment::where('status', 'Declined')->count();
            $approvedCount = PreAssessment::where('status', 'Approved')->count();
            $notDelayedCount = PreAssessment::where('status', 'Pending')
                ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) > CURDATE()")
                ->count();
            $delayedCount = PreAssessment::where('status', 'Pending')
                ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) < CURDATE()")
                ->count();
        }

        if (auth()->user()->role == "Document Control Officer") {
            $pre_assessment = PreAssessment::whereHas('approvers', function ($query) use ($loggedInUserId) {
                $query->where('user_id', $loggedInUserId);
            })
            ->when($request->status, function($query) use ($request) {
                if ($request->status == 'Delayed') {
                    $query->where('status', 'Pending')
                          ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) < CURDATE()");
                }elseif ($request->status == 'NotDelayed') {
                    $query->where('status', 'Pending')
                          ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) > CURDATE()");
                } else {
                    $query->where('status', $request->status);
                }
            })
            // ->when($request->status, function($query) use ($request) {
            //     $query->where('status', $request->status);
            // })
            ->orderBy('id', 'desc')->get();
        } elseif (auth()->user()->role == "Administrator") {
            $pre_assessment = PreAssessment::when($request->status, function($query) use ($request) {
                if ($request->status == 'Delayed') {
                    $query->where('status', 'Pending')
                          ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) < CURDATE()");
                }elseif ($request->status == 'NotDelayed') {
                    $query->where('status', 'Pending')
                          ->whereRaw("DATE_ADD(created_at, INTERVAL 10 DAY) > CURDATE()");
                } else {
                    $query->where('status', $request->status);
                }
            })->orderBy('id','desc')->get();
        } else {
            $pre_assessment = collect();
        }
        return view('pre_assessment',
            array(
                'departments' => $departments,
                'companies' => $companies,
                'document_types' => $document_types,
                'approver' => $approver,
                'pre_assessment' => $pre_assessment,
                'pendingCount' => $pendingCount,
                'declinedCount' => $declinedCount,
                'approvedCount' => $approvedCount,
                'notDelayedCount' => $notDelayedCount,
                'delayedCount' => $delayedCount,
            )
        );
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

    public function approve(Request $request, $id)
    {
        // dd($request->all());
        $preAssessment = PreAssessment::findOrFail($id);
        
        if ($request->action == "Approved")
        {
            $preAssessmentApprover = PreAssessmentApprover::where('pre_assessment_id', $id)->first();
            $preAssessmentApprover->status = "Approved";
            $preAssessmentApprover->remarks = $request->remarks;
            $preAssessmentApprover->save();

            $preAssessment->status = $request->action;
            $preAssessment->save();

            $changeRequest = ChangeRequest::where('pre_assessment_id', $preAssessment->id)->first();
            
            $departmentApprover = DepartmentApprover::where('department_id', $preAssessment->department_id)->get();
            foreach($departmentApprover as $approver)
            {
                $requestApprover = new RequestApprover;
                $requestApprover->change_request_id = $changeRequest->id;
                $requestApprover->user_id = $approver->user_id;
                $requestApprover->level = $approver->level;

                if($approver->level == 1)
                {
                    $requestApprover->status = "Pending";
                    $requestApprover->start_date = date('Y-m-d');
                }
                else
                {
                    $requestApprover->status = "Waiting";
                
                }
                $requestApprover->level = $approver->level;

                $requestApprover->save();
            }
        }
        elseif($request->action == "Declined")
        {
            $preAssessmentApprover = PreAssessmentApprover::where('pre_assessment_id', $id)->first();
            $preAssessmentApprover->status = "Declined";
            $preAssessmentApprover->remarks = $request->remarks;
            $preAssessmentApprover->save();

            $preAssessment->status = $request->action;
            $preAssessment->save();
            
            $changeRequest = ChangeRequest::where('pre_assessment_id', $preAssessment->id)->first();
            $changeRequest->delete();
            
            $user = User::where('id', $preAssessment->user_id)->first();
            $user->notify(new DeclinePreAssessmentNotification($preAssessment->title, $preAssessment->request_type, $preAssessmentApprover->remarks));
        }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }
}
