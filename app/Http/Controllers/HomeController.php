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
        $departments = Department::where('status', 'active')
            ->orderBy('code')
            ->get();

        $offices = Office::where('status', 'Active')
            ->orderBy('name')
            ->get();

        $documentQuery = Document::with('attachments', 'department')->where('public', 1);

        $docRaw = $request->get('doc_office_search', '');
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

        if (auth()->user()->role != "Administrator")
        {
            $pending_query = ChangeRequest::where('user_id', auth()->user()->id)
                                        ->where('status', 'For Approval')
                                        ->where('request_status', 'Pending');
            $table_query = ChangeRequest::where('user_id', auth()->user()->id);
        }
        else
        {
            $pending_query = ChangeRequest::where('status', 'For Approval')
                                        ->where('request_status', 'Pending');
            $table_query = ChangeRequest::query();
        }

        $pendingRaw = $request->get('pending_search', '');
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

        $pending_cards = $pending_query->with(['department.office', 'user'])->orderBy('created_at', 'desc')->paginate(4, ['*'], 'pending_page');
        $change_requests = $table_query->paginate($perPage, ['*'], 'table_page');
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

        return view('home',
        array(
            // 'permits' =>  $permits,
            'change_requests' =>  $change_requests,
            'documents' =>  $documents,
            'pending_cards' => $pending_cards,
            'departments' => $departments,
            'offices' => $offices,
            // 'categories' =>  $categories,
            // 'copy_requests' =>  $copy_requests,
            // 'months' =>  $months,
            // 'yearChangeRequests' =>  $yearChangeRequests,
            // 'yearCopyRequests' =>  $yearCopyRequests,

        ));
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
}