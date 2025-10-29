<?php

namespace App\Http\Controllers;

use App\Permit;
use App\Department;
use App\Document;
use App\ChangeRequest;
use App\CopyRequest;
use App\DocumentType;
use App\Company;
use Illuminate\Http\Request;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $change_requests = ChangeRequest::get();
        $copy_requests = CopyRequest::get();

        $yearChangeRequests = ChangeRequest::whereYear('created_at',date('Y'))->get();
        $yearCopyRequests = CopyRequest::whereYear('created_at',date('Y'))->get();
        $documents = Document::where('status',null)->get();
        $departments = Department::whereHas('documents')->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
        $permits = Permit::with('company', 'department')->get();
        $months = [];
       
        for ($m=1; $m<=12; $m++) {
            $object = new \stdClass();
            $object->y =date('M-Y', mktime(0,0,0,$m, 1, date('Y')));
            $change_requests_count = ChangeRequest::whereYear('created_at',date('Y'))->whereMonth('created_at',date('m',mktime(0,0,0,$m, 1, date('Y'))))->count();
            $copy_requests_count = CopyRequest::whereYear('created_at',date('Y'))->whereMonth('created_at',date('m',mktime(0,0,0,$m, 1, date('Y'))))->count();
            $object->a =$change_requests_count;
            $object->b =$copy_requests_count;
            $months[$m-1]=  $object;
        }
        // dd($months);
        if((auth()->user()->role != "Administrator") || (auth()->user()->role != "Management Representative") || (auth()->user()->role != "Business Process Manager"))
        {
            if((auth()->user()->role == "Department Head"))
            {
                $departments = Department::whereIn('id',(auth()->user()->department_head)->pluck('id')->toArray())->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
                $change_requests = ChangeRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
                $copy_requests = CopyRequest::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
                $documents = Document::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->where('status',null)->toArray())->get();
                $permits = Permit::with('company', 'department')->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
           
            }
            elseif((auth()->user()->role == "Documents and Records Controller"))
            {
                $departments = Department::where('id',auth()->user()->department_id)->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
                $change_requests = ChangeRequest::where('user_id',auth()->user()->id)->get();
                $copy_requests = CopyRequest::where('user_id',auth()->user()->id)->get();
                $documents = Document::where('department_id',auth()->user()->department_id)->where('status',null)->get();
                $permits = Permit::with('company', 'department')->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
           

            }
            elseif((auth()->user()->role == "Document Control Officer"))
            {
                $departments = Department::whereIn('id',(auth()->user()->dco)->pluck('department_id')->toArray())->with('documents','obsoletes')->withCount('documents','obsoletes')->get();
                $change_requests = ChangeRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
                $copy_requests = CopyRequest::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
                $documents = Document::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status',null)->get();
                $permits = Permit::with('company', 'department')->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
            }

        }

        $categories = DocumentType::get();
        return view('home',
        array(
            'permits' =>  $permits,
            'departments' =>  $departments,
            'change_requests' =>  $change_requests,
            'documents' =>  $documents,
            'categories' =>  $categories,
            'copy_requests' =>  $copy_requests,
            'months' =>  $months,
            'yearChangeRequests' =>  $yearChangeRequests,
            'yearCopyRequests' =>  $yearCopyRequests,

        ));
    }
    public function search(Request $request)
    {
        $documents = [];
        $comp=$request->company;
        $dept=$request->department;
        $companies = Company::get();
        $departments = Department::get();
        
        $request_documents = Document::where('public','!=',null)->where('status',null)->orderBy('control_code','asc')->get();
        $documents_filter = Document::query();
        if($request->department)
        {
            $documents = $documents_filter->where('department_id',$request->department)->orderBy('old_control_code', 'DESC')->get();
        }
        if($request->company)
        {
            $documents = $documents_filter->where('company_id',$request->company)->orderBy('old_control_code', 'DESC')->get();
        }
        if($request->search)
        {
            if($request->department)
            {
                $documents = $documents_filter->where('control_code','like','%' . $request->search. '%')->orWhere('old_control_code','like','%' . $request->search. '%')->orWhere('title','like','%' . $request->search. '%')->where('status',null)->where('department_id', $request->department)->get();
            }
            else 
            {
                $documents = $documents_filter->where('control_code','like','%' . $request->search. '%')->orWhere('old_control_code','like','%' . $request->search. '%')->orWhere('title','like','%' . $request->search. '%')->where('status',null)->get();
            }
        }
       
        $departments = Department::with('documents','obsoletes')->whereHas('documents')->orWhereHas('obsoletes')->get();

        $getWHIDepartments = Department::where('code', 'LIKE', "%WHI%")->where('status', null)->where('id', $request->department)->first();
        
        return view('search',
        array(
            'documents' => $documents,
            'search' => $request->search,
            'request_documents' => $request_documents,
            'companies' => $companies,
            'departments' => $departments,
            'comp' => $comp,
            'dept' => $dept,
            'whiDept' => $getWHIDepartments
        ));
    }
}
