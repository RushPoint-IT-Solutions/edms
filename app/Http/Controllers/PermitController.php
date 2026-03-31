<?php

namespace App\Http\Controllers;

use App\Department;
use App\Company;
use App\Archive;
use App\Permit;
use App\User;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Notifications\ForRenewal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PermitController extends Controller
{
    public function index(Request $request)
    {
        if (!canView('permits_and_license.view')) {
            return view('pages.403-error');
        }

        $permits_count = Permit::count();
        $permits = Permit::get();
        $for_renewal_count = Permit::where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null)->count();
        $overdue_count = Permit::where('expiration_date', '<', date('Y-m-d'))->where('status', null)->count();
        $active_permits_count = Permit::where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null)->count();
        $inactive_permits_count = Permit::where('status', 'Inactive')->count();
        // $archives = Archive::get();
    
        // if(auth()->user()->role == "Document Control Officer")
        // { 
        //     $permits = Permit::with('company', 'department')
        //         ->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())
        //         ->when($request->renewal_filter, function($q) {
        //             $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null);
        //         })
        //         ->when($request->overdue_filter, function($q) {
        //             $q->where('expiration_date', '<', date('Y-m-d'))->where('status',null);
        //         })
        //         ->when($request->active_permits_filter, function($q) {
        //             $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null);
        //         })
        //         ->when($request->inactive_filter, function($q) {
        //             $q->where('status', 'Inactive');
        //         })
        //         ->get();

        //     $permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status',null)->count();
        //     $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
        //     $overdue_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
        //     $active_permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
        //     $inactive_permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status', 'Inactive')->count();
        //     $departments = Department::whereHas('permit_accounts')->whereIn('id',((auth()->user()->dco)->pluck('department_id')->toArray()))->where('status', '=', null)->get();
        // }
        
        // if((auth()->user()->role == "Department Head"))
        // {
        //     $permits = Permit::with('company', 'department')
        //         // ->whereIn('department_id',(auth()->user()->permits)->pluck('department_id')->toArray())
        //         ->where('department_id', auth()->user()->department_id)
        //         ->when($request->renewal_filter, function($q) {
        //             $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
        //         })
        //         ->when($request->overdue_filter, function($q) {
        //             $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
        //         })
        //         ->when($request->active_permits_filter, function($q) {
        //             $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
        //         })
        //         ->when($request->inactive_filter, function($q) {
        //             $q->where('status', 'Inactive');
        //         })
        //         ->get();
        //     // $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->permits)->pluck('department_id')->toArray())->where('status', '=', null)->get();
        //     $departments = Department::whereHas('permit_accounts')->where('id',auth()->user()->department_id)->where('status', '=', null)->get();
        //     $permits_count = Permit::where('department_id',auth()->user()->department_id)->where('status',null)->count();
        //     $for_renewal_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
        //     $overdue_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
        //     $active_permits_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
        //     $inactive_permits_count = Permit::where('department_id',auth()->user()->department_id)->where('status', 'Inactive')->count();
        //     $archives = Archive::where('department_id', auth()->user()->department_id)->get();
        // }
        // if((auth()->user()->role == "User"))
        // {
        //     $permits = Permit::with('company', 'department')
        //         ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
        //         ->when($request->renewal_filter, function($q) {
        //             $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
        //         })
        //         ->when($request->overdue_filter, function($q) {
        //             $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
        //         })
        //         ->when($request->active_permits_filter, function($q) {
        //             $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
        //         })
        //         ->when($request->inactive_filter, function($q) {
        //             $q->where('status', 'Inactive');
        //         })
        //         ->get();
        //     $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();
        //     $permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status',null)->count();
        //     $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
        //     $overdue_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
        //     $active_permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
        //     $inactive_permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', 'Inactive')->count();
        //     $archives = Archive::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
        // }
        // if((auth()->user()->role == "Documents and Records Controller"))
        // {
        //     $permits = Permit::with('company', 'department')
        //         ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
        //         ->when($request->renewal_filter, function($q) {
        //             $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'));
        //         })
        //         ->when($request->overdue_filter, function($q) {
        //             $q->where('expiration_date', '<', date('Y-m-d'));
        //         })
        //         ->get();
        //     $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();
        // }

        return view('permits.permits', array(
            // 'companies' => $companies,
            // 'departments' => $departments,
            // 'archives' => $archives,
            'permits' => $permits,
            'for_renewal_count' => $for_renewal_count,
            'overdue_count' => $overdue_count,
            'permits_count' => $permits_count,
            'active_permits_count' => $active_permits_count,
            'inactive_permits_count' => $inactive_permits_count,
        ));
    }

    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $statusFilter = $request->get('status_filter', '');

        $today = date('Y-m-d');
        $threeMonths = date('Y-m-d', strtotime("+3 months", strtotime($today)));

        $query = Permit::with('user');

        if (!empty($statusFilter)) {
            if ($statusFilter === 'Inactive') {
                $query->where('status', 'Inactive');
            } elseif ($statusFilter === 'Active') {
                $query->where('expiration_date', '>', $today)
                      ->where('expiration_date', '>', $threeMonths)
                      ->whereNull('status');
            } elseif ($statusFilter === 'For Renewal') {
                $query->where('expiration_date', '<', $threeMonths)
                      ->where('expiration_date', '>', $today)
                      ->whereNull('status');
            } elseif ($statusFilter === 'Overdue') {
                $query->where('expiration_date', '<', $today)
                      ->whereNull('status');
            }
        }

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('expiration_date', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $query->count();

        $permits = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($permits as $permit) {

            if ($permit->status === 'Inactive') {
                $statusBadge = '<span class="badge bg-secondary">Inactive</span>';
            } elseif ($permit->expiration_date !== null) {
                if ($permit->expiration_date < $today) {
                    $statusBadge = '<span class="badge bg-danger">For Renewal (Overdue)</span>';
                } elseif ($permit->expiration_date < $threeMonths) {
                    $statusBadge = '<span class="badge bg-warning text-dark">For Renewal</span>';
                } else {
                    $statusBadge = '<span class="badge bg-success">Active</span>';
                }
            } else {
                $statusBadge = '<span class="badge bg-secondary">—</span>';
            }

            $fileLink = $permit->file
                ? '<a href="' . url($permit->file) . '" target="_blank"><i class="ri-file-line"></i></a>'
                : '—';

            $data[] = [
                'action' => '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#upload' . $permit->id . '">
                                    <i class="ri-upload-line me-2"></i>Upload
                                </a>
                            </li>
                            <!-- <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#change' . $permit->id . '">
                                    <i class="ri-user-line me-2"></i>Transfer Department
                                </a>
                            </li> -->
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changeType' . $permit->id . '">
                                    <i class="ri-edit-line me-2"></i>Change Types
                                </a>
                            </li>
                            <!-- @if($permit->status == null)
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ url(\'inactive-permits/\' . $permit->id) }}" style="display:inline-block;width:100%;">
                                        @csrf
                                        <button type="button" class="dropdown-item text-danger inactiveBtn">
                                            <i class="ri-delete-bin-line me-2"></i>Inactive Permits
                                        </button>
                                    </form>
                                </li>
                            @endif -->
                            <!-- @if($permit->status == "Inactive")
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ url(\'activate-permits/\' . $permit->id) }}" style="display:inline-block;width:100%;">
                                        @csrf
                                        <button type="button" class="dropdown-item text-success activatePermitsBtn">
                                            <i class="ri-check-line me-2"></i>Activate Permits
                                        </button>
                                    </form>
                                </li>
                            @endif -->
                        </ul>
                    </div>',
                'title' => e($permit->title),
                'description' => e($permit->description),
                // 'company' => optional($permit->company)->name ?? '—',
                // 'department' => optional($permit->department)->name ?? '—',
                'date_uploaded' => date('M d, Y', strtotime($permit->created_at)),
                'file' => $fileLink,
                'type' => e($permit->type),
                'expiration_date' => $permit->expiration_date ? date('M d, Y', strtotime($permit->expiration_date)) : '—',
                'status' => $statusBadge,
                'created_by' => $permit->user->name ?? '—',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
            'type' => 'required|in:License,Permit,Certification',
            'file' => 'required',
            'expiration_date' => 'required|date',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $attachment = $request->file('file');
            $name = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path() . '/permits_attachments/', $name);
            $file_name = '/permits_attachments/' . $name;

            $permit = new Permit;
            $permit->title = $request->title;
            $permit->description = $request->description;
            // $permit->company_id = $request->company;
            // $permit->department_id = $request->department;
            $permit->type = $request->type;
            $permit->file = $file_name;
            $permit->expiration_date = $request->expiration_date;
            $permit->user_id = auth()->user()->id;
            $permit->save();

            return response()->json(['status' => 'success', 'message' => 'Successfully Saved'], 200);
        }
        catch (\Throwable $e) {
            Log::error("Error in creating permits ". $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 500);
        }

        // Alert::success('Successfully Save')->persistent('Dismiss');
        // return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    // public function update(Request $request, $id)
    // {
    //     //
    //     $permit = Permit::findOrfail($id);
    //     $permit->department_id = $request->department;
    //     $permit->save();

    //     Alert::success('Successfully Updated')->persistent('Dismiss');
    //     return back();
    // }

    public function change_type(Request $request, $id)
    {
        $permit = Permit::findOrfail($id);
        $permit->type = $request->type;
        $permit->title = $request->title;
        $permit->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function destroy($id)
    {
        //
    }

    public function upload(Request $request, $id)
    {
        $this->validate($request, [
            'file' => 'required',
            // 'expiration_date' => 'required',
        ]);

        $attachment = $request->file('file');
        $name = time() . '_' . $attachment->getClientOriginalName();
        $attachment->move(public_path() . '/permits_attachments/', $name);
        $file_name = '/permits_attachments/' . $name;

        $permit = Permit::findOrfail($id);
        $archive = new Archive;
        $archive->permit_id = $id;
        $archive->title = $permit->title;
        $archive->description = $permit->description;
        $archive->company_id = $permit->company_id;
        $archive->department_id = $permit->department_id;
        $archive->file = $permit->file;
        $archive->expiration_date = $permit->expiration_date;
        $archive->user_id = $permit->user_id;
        $archive->type = $permit->type;
        $archive->save();

        $permit->file = $file_name;
        $permit->expiration_date = $request->expiration_date;
        $permit->user_id = auth()->user()->id;
        $permit->save();

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
    }

    // public function email_notif()
    // {
    //     $users = User::where('status',null)->get();
    //     foreach($users as $user)
    //     {
    //         $permits = Permit::with('company', 'department')->get();
            
    //         if($user->role == "Document Control Officer")
    //         { 
    //             $permits = Permit::with('company', 'department')->whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->get();
    //         }
    //         if(($user->role == "Department Head"))
    //         {
    //             $permits = Permit::with('company', 'department')->whereIn('department_id',($user->permits)->pluck('department_id')->toArray())->get();
    //         }
    //         if(($user->role == "User"))
    //         {
    //             $permits = Permit::with('company', 'department')->whereIn('department_id',($user->accountable_persons)->pluck('department_id')->toArray())->get();
    //         }
    //         if(($user->role == "Documents and Records Controller"))
    //         {
    //             $permits = Permit::with('company', 'department')->whereIn('department_id',($user->accountable_persons)->pluck('department_id')->toArray())->get();
    //         }

    //         $countPermit = count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))));
    //         $countOverdue = count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')));
    //         if($countPermit > 0)
    //         {
    //             $user->notify(new ForRenewal($countPermit,$countOverdue));
    //         }
    //     }
    // }

    // public function viewArchived(Request $request)
    // {
    //     $companies = Company::where('status', '=', null)->get();
    //     $departments = Department::whereHas('permit_accounts')->where('status', '=', null)->get();
    //     $permits = Permit::with('company', 'department')
    //         ->when($request->renewal_filter, function($q) {
    //             $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
    //         })
    //         ->when($request->overdue_filter, function($q) {
    //             $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
    //         })
    //         ->when($request->active_permits_filter, function($q) {
    //             $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
    //         })
    //         ->get();
        
    //     $active_permits_count = Permit::where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null)->count();
    //     $inactive_count = Permit::where('status', 'Inactive')->get();
    //     $overdue_count = Permit::where('expiration_date', '<', date('Y-m-d'))->where('status', null)->count();
    //     $for_renewal_count = Permit::where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null)->count();
    //     $permits_count = Permit::count();
    //     $archives = Archive::with('department', 'company')->get();

    //     if(auth()->user()->role == "Document Control Officer")
    //     { 
    //         $permits = Permit::with('company', 'department')
    //             ->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())
    //             ->get();
    //         $active_permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->count();
    //         $inactive_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status', 'Inactive')->get();
    //         $overdue_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status', null)->count();
    //         $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null)->count();
    //         $departments = Department::whereHas('permit_accounts')->whereIn('id',((auth()->user()->dco)->pluck('department_id')->toArray()))->where('status', '=', null)->get();
    //     }
    //     if((auth()->user()->role == "Department Head"))
    //     {
    //         // $permits = Permit::with('company', 'department')
    //         //     ->whereIn('department_id',(auth()->user()->permits)->pluck('department_id')->toArray())
    //         //     ->get();
    //         // $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->permits)->pluck('department_id')->toArray())->where('status', '=', null)->get();
    //         $permits = Permit::with('company', 'department')
    //             // ->whereIn('department_id',(auth()->user()->permits)->pluck('department_id')->toArray())
    //             ->where('department_id', auth()->user()->department_id)
    //             ->when($request->renewal_filter, function($q) {
    //                 $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'));
    //             })
    //             ->when($request->overdue_filter, function($q) {
    //                 $q->where('expiration_date', '<', date('Y-m-d'));
    //             })
    //             ->get();
    //         // $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->permits)->pluck('department_id')->toArray())->where('status', '=', null)->get();
    //         $departments = Department::whereHas('permit_accounts')->where('id',auth()->user()->department_id)->where('status', '=', null)->get();
    //         $permits_count = Permit::where('department_id',auth()->user()->department_id)->where('status',null)->count();
    //         $for_renewal_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
    //         $overdue_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
    //         $active_permits_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
    //         $inactive_count = Permit::where('department_id',auth()->user()->department_id)->where('status', 'Inactive')->get();
    //         $archives = Archive::with('department', 'company')->where('department_id', auth()->user()->department_id)->get();
    //     }
    //     if((auth()->user()->role == "User"))
    //     {
    //         $permits = Permit::with('company', 'department')
    //             ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
    //             ->get();
    //         $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();
    //         $permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status',null)->count();
    //         $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
    //         $overdue_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
    //         $active_permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
    //         $inactive_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', 'Inactive')->get();
    //         $archives = Archive::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
    //     }
    //     if((auth()->user()->role == "Documents and Records Controller"))
    //     {
    //         $permits = Permit::with('company', 'department')
    //             ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
    //             ->get();
    //         $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();
    //     }

    //     return view('view_archive', array(
    //         'companies' => $companies,
    //         'departments' => $departments,
    //         'permits' => $permits,
    //         'archives' => $archives,
    //         'active_permits_count' => $active_permits_count,
    //         'inactive_count' => $inactive_count,
    //         'overdue_count' => $overdue_count,
    //         'for_renewal_count' => $for_renewal_count,
    //         'permits_count' => $permits_count
    //     ));
    // }

    public function inactivePermits($id)
    {
        $permits = Permit::findOrFail($id);
        $permits->status = "Inactive";
        $permits->save();
        
        Alert::success('Successfully Inactive')->persistent('Dismiss');
        return back();
    }

    public function activatePermits($id)
    {
        $permits = Permit::findOrFail($id);
        $permits->status = null;
        $permits->save();
        
        Alert::success('Successfully Activate')->persistent('Dismiss');
        return back();
    }
}