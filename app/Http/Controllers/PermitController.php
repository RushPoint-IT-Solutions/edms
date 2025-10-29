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

class PermitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $companies = Company::where('status', '=', null)->get();
        $departments = Department::whereHas('permit_accounts')->where('status', '=', null)->get();
        $permits = Permit::with('company', 'department')
            ->when($request->renewal_filter, function($q) {
                $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
            })
            ->when($request->overdue_filter, function($q) {
                $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
            })
            ->when($request->active_permits_filter, function($q) {
                $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
            })
            ->when($request->inactive_filter, function($q) {
                $q->where('status', 'Inactive');
            })
            ->get();

        $permits_count = Permit::count();
        $for_renewal_count = Permit::where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null)->count();
        $overdue_count = Permit::where('expiration_date', '<', date('Y-m-d'))->where('status', null)->count();
        $active_permits_count = Permit::where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null)->count();
        $inactive_permits_count = Permit::where('status', 'Inactive')->count();
        $archives = Archive::get();
    
        if(auth()->user()->role == "Document Control Officer")
        { 
            $permits = Permit::with('company', 'department')
                ->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())
                ->when($request->renewal_filter, function($q) {
                    $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null);
                })
                ->when($request->overdue_filter, function($q) {
                    $q->where('expiration_date', '<', date('Y-m-d'))->where('status',null);
                })
                ->when($request->active_permits_filter, function($q) {
                    $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null);
                })
                ->when($request->inactive_filter, function($q) {
                    $q->where('status', 'Inactive');
                })
                ->get();

            $permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status',null)->count();
            $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
            $overdue_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
            $active_permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
            $inactive_permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status', 'Inactive')->count();

            $departments = Department::whereHas('permit_accounts')->whereIn('id',((auth()->user()->dco)->pluck('department_id')->toArray()))->where('status', '=', null)->get();
        }
        
        if((auth()->user()->role == "Department Head"))
        {
            $permits = Permit::with('company', 'department')
                // ->whereIn('department_id',(auth()->user()->permits)->pluck('department_id')->toArray())
                ->where('department_id', auth()->user()->department_id)
                ->when($request->renewal_filter, function($q) {
                    $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
                })
                ->when($request->overdue_filter, function($q) {
                    $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
                })
                ->when($request->active_permits_filter, function($q) {
                    $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
                })
                ->when($request->inactive_filter, function($q) {
                    $q->where('status', 'Inactive');
                })
                ->get();
            // $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->permits)->pluck('department_id')->toArray())->where('status', '=', null)->get();
            $departments = Department::whereHas('permit_accounts')->where('id',auth()->user()->department_id)->where('status', '=', null)->get();

            $permits_count = Permit::where('department_id',auth()->user()->department_id)->where('status',null)->count();
            $for_renewal_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
            $overdue_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
            $active_permits_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
            $inactive_permits_count = Permit::where('department_id',auth()->user()->department_id)->where('status', 'Inactive')->count();
            $archives = Archive::where('department_id', auth()->user()->department_id)->get();
        }
        if((auth()->user()->role == "User"))
        {
            $permits = Permit::with('company', 'department')
                ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
                ->when($request->renewal_filter, function($q) {
                    $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
                })
                ->when($request->overdue_filter, function($q) {
                    $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
                })
                ->when($request->active_permits_filter, function($q) {
                    $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
                })
                ->when($request->inactive_filter, function($q) {
                    $q->where('status', 'Inactive');
                })
                ->get();
            $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();

            $permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status',null)->count();
            $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
            $overdue_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
            $active_permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
            $inactive_permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', 'Inactive')->count();

            $archives = Archive::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
        }
        if((auth()->user()->role == "Documents and Records Controller"))
        {
            $permits = Permit::with('company', 'department')
                ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
                ->when($request->renewal_filter, function($q) {
                    $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'));
                })
                ->when($request->overdue_filter, function($q) {
                    $q->where('expiration_date', '<', date('Y-m-d'));
                })
                ->get();
            $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();
        }
       
        return view('permits', array(
            'companies' => $companies,
            'departments' => $departments,
            'permits' => $permits,
            'archives' => $archives,
            'for_renewal_count' => $for_renewal_count,
            'overdue_count' => $overdue_count,
            'permits_count' => $permits_count,
            'active_permits_count' => $active_permits_count,
            'inactive_permits_count' => $inactive_permits_count
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
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'department' => 'required',
            'type' => 'required',
            'file' => 'required',
        ]);

        $attachment = $request->file('file');
        $name = time() . '_' . $attachment->getClientOriginalName();
        $attachment->move(public_path() . '/permits_attachments/', $name);
        $file_name = '/permits_attachments/' . $name;

        $permit = new Permit;
        $permit->title = $request->title;
        $permit->description = $request->description;
        $permit->company_id = $request->company;
        $permit->department_id = $request->department;
        $permit->type = $request->type;
        $permit->file = $file_name;
        $permit->expiration_date = $request->expiration_date;
        $permit->user_id = auth()->user()->id;
        $permit->save();

        Alert::success('Successfully Save')->persistent('Dismiss');
        return back();
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
        $permit = Permit::findOrfail($id);
        $permit->department_id = $request->department;
        $permit->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function change_type(Request $request, $id)
    {
        //
        $permit = Permit::findOrfail($id);
        $permit->type = $request->type;
        $permit->title = $request->title;
        $permit->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
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
    public function email_notif()
    {
        $users = User::where('status',null)->get();
        foreach($users as $user)
        {
            $permits = Permit::with('company', 'department')->get();
            
            if($user->role == "Document Control Officer")
            { 
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->dco)->pluck('department_id')->toArray())->get();
            }
            if(($user->role == "Department Head"))
            {
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->permits)->pluck('department_id')->toArray())->get();
            }
            if(($user->role == "User"))
            {
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->accountable_persons)->pluck('department_id')->toArray())->get();
            }
            if(($user->role == "Documents and Records Controller"))
            {
                $permits = Permit::with('company', 'department')->whereIn('department_id',($user->accountable_persons)->pluck('department_id')->toArray())->get();
            }

            $countPermit = count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))));
            $countOverdue = count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')));
            if($countPermit > 0)
            {
                $user->notify(new ForRenewal($countPermit,$countOverdue));
            }
        }
    }

    public function viewArchived(Request $request)
    {
        $companies = Company::where('status', '=', null)->get();
        $departments = Department::whereHas('permit_accounts')->where('status', '=', null)->get();
        $permits = Permit::with('company', 'department')
            ->when($request->renewal_filter, function($q) {
                $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null);
            })
            ->when($request->overdue_filter, function($q) {
                $q->where('expiration_date', '<', date('Y-m-d'))->where('status', null);
            })
            ->when($request->active_permits_filter, function($q) {
                $q->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null);
            })
            ->get();
        
        $active_permits_count = Permit::where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status', null)->count();
        $inactive_count = Permit::where('status', 'Inactive')->get();
        $overdue_count = Permit::where('expiration_date', '<', date('Y-m-d'))->where('status', null)->count();
        $for_renewal_count = Permit::where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null)->count();
        $permits_count = Permit::count();
        $archives = Archive::with('department', 'company')->get();

        if(auth()->user()->role == "Document Control Officer")
        { 
            $permits = Permit::with('company', 'department')
                ->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())
                ->get();

            $active_permits_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->count();
            $inactive_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('status', 'Inactive')->get();
            $overdue_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status', null)->count();
            $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status', null)->count();

            $departments = Department::whereHas('permit_accounts')->whereIn('id',((auth()->user()->dco)->pluck('department_id')->toArray()))->where('status', '=', null)->get();
        }
        if((auth()->user()->role == "Department Head"))
        {
            // $permits = Permit::with('company', 'department')
            //     ->whereIn('department_id',(auth()->user()->permits)->pluck('department_id')->toArray())
            //     ->get();
            // $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->permits)->pluck('department_id')->toArray())->where('status', '=', null)->get();
            $permits = Permit::with('company', 'department')
                // ->whereIn('department_id',(auth()->user()->permits)->pluck('department_id')->toArray())
                ->where('department_id', auth()->user()->department_id)
                ->when($request->renewal_filter, function($q) {
                    $q->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'));
                })
                ->when($request->overdue_filter, function($q) {
                    $q->where('expiration_date', '<', date('Y-m-d'));
                })
                ->get();
            // $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->permits)->pluck('department_id')->toArray())->where('status', '=', null)->get();
            $departments = Department::whereHas('permit_accounts')->where('id',auth()->user()->department_id)->where('status', '=', null)->get();

            $permits_count = Permit::where('department_id',auth()->user()->department_id)->where('status',null)->count();
            $for_renewal_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
            $overdue_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
            $active_permits_count = Permit::where('department_id',auth()->user()->department_id)->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
            $inactive_count = Permit::where('department_id',auth()->user()->department_id)->where('status', 'Inactive')->get();
            $archives = Archive::with('department', 'company')->where('department_id', auth()->user()->department_id)->get();
        }
        if((auth()->user()->role == "User"))
        {
            $permits = Permit::with('company', 'department')
                ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
                ->get();
            $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();

            $permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status',null)->count();
            $for_renewal_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('expiration_date', '>',  date('Y-m-d'))->where('status',null)->count();
            $overdue_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '<', date('Y-m-d'))->where('status',null)->count();
            $active_permits_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('expiration_date', '>', date('Y-m-d'))->where('expiration_date', '>', date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))->where('status',null)->count();
            $inactive_count = Permit::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', 'Inactive')->get();

            $archives = Archive::whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->get();
        }
        if((auth()->user()->role == "Documents and Records Controller"))
        {
            $permits = Permit::with('company', 'department')
                ->whereIn('department_id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())
                ->get();
            $departments = Department::whereHas('permit_accounts')->whereIn('id',(auth()->user()->accountable_persons)->pluck('department_id')->toArray())->where('status', '=', null)->get();
        }

        return view('view_archive', array(
            'companies' => $companies,
            'departments' => $departments,
            'permits' => $permits,
            'archives' => $archives,
            'active_permits_count' => $active_permits_count,
            'inactive_count' => $inactive_count,
            'overdue_count' => $overdue_count,
            'for_renewal_count' => $for_renewal_count,
            'permits_count' => $permits_count
        ));
    }

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
