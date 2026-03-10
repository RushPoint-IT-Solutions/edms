<?php

namespace App\Http\Controllers;

use App\Department;
use App\DepartmentApprover;
use App\PermitAccountable;
use App\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalDepartments = Department::count();
        $activeDepartments = Department::where('status', 1)->count();
        $inactiveDepartments = Department::where('status', 0)->count();
        $departments = Department::with('dep_head')->get();
        $employees = User::all();

        return view('departments.departments', compact('totalDepartments', 'activeDepartments', 'inactiveDepartments', 'departments', 'employees'));
    }

    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $statusFilter = $request->get('status_filter');

        $query = Department::with(['dep_head:id,name']);

        $totalRecords = (clone $query)->count();

        if (!empty($statusFilter)) {
            if ($statusFilter === 'Active') {
                $query->where('status', 1);
            } else {
                $query->where('status', 0);
            }
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%");
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $department) {
            $status = $department->status == 1
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';

            $depHead = $department->dep_head
                ? $department->dep_head->name
                : '<span class="text-muted">No Head</span>';

            if ($department->status == 0) {
                $actions = '
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item activate-department" data-id="' . $department->id . '">
                            <i class="ri-check-line me-2"></i>Activate
                        </button></li>
                    </ul>';
            } else {
                $actions = '
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editDepartment' . $department->id . '">
                            <i class="ri-pencil-line me-2"></i>Edit
                        </button></li>
                        <li><button class="dropdown-item deactivate-department" data-id="' . $department->id . '">
                            <i class="ri-close-line me-2"></i>Deactivate
                        </button></li>
                    </ul>';
            }

            $data[] = [
                'action' => '<div class="dropdown">' . $actions . '</div>',
                'code' => '<strong>' . $department->code . '</strong>',
                'name' => $department->name ?? 'N/A',
                'dep_head' => $depHead,
                'status' => $status,
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
        $this->validate($request, [
            'code' => 'required|min:2|max:50|unique:departments',
            'name' => 'required',
            'user_id' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $department = new Department;
            $department->code = $request->code;
            $department->name = $request->name;
            $department->user_id = $request->user_id;
            $department->status = 1;
            $department->save();

            if ($request->approvers) {
                $approversData = [];
                foreach($request->approvers as $key => $approver) {
                    $approversData[] = [
                        'department_id' => $department->id,
                        'user_id' => $approver,
                        'level' => $key + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DepartmentApprover::insert($approversData);
            }

            if($request->permit_id) {
                $permitData = [];
                foreach($request->permit_id as $permit_id) {
                    $permitData[] = [
                        'department_id' => $department->id,
                        'user_id' => $permit_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                PermitAccountable::insert($permitData);
            }

            \DB::commit();
            Alert::success('Successfully Store')->persistent('Dismiss');
        } catch (\Exception $e) {
            \DB::rollback();
            Alert::error('Error occurred')->persistent('Dismiss');
        }

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
        $this->validate($request, [
            'name' => 'required',
            'user_id' => 'required',
        ]);
        
        \DB::beginTransaction();
        try {
            $department = Department::findOrfail($id);
            $department->name = $request->name;
            $department->code = $request->code;
            $department->user_id = $request->user_id;
            $department->save();

            // DepartmentApprover::where('department_id', $id)->delete();
            // PermitAccountable::where('department_id', $id)->delete();

            // if ($request->edit_approvers) {
            //     $approversData = [];
            //     foreach($request->edit_approvers as $key => $approver) {
            //         $approversData[] = [
            //             'department_id' => $department->id,
            //             'user_id' => $approver,
            //             'level' => $key + 1,
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ];
            //     }
            //     DepartmentApprover::insert($approversData);
            // }

            // if($request->permit_id != null) {
            //     $permitData = [];
            //     foreach($request->permit_id as $permit_id) {
            //         $permitData[] = [
            //             'department_id' => $department->id,
            //             'user_id' => $permit_id,
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ];
            //     }
            //     PermitAccountable::insert($permitData);
            // }

            \DB::commit();
            Alert::success('Successfully Updated')->persistent('Dismiss');
        } catch (\Exception $e) {
            \DB::rollback();
            Alert::error('Error occurred')->persistent('Dismiss');
        }

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
    
    public function deactivate(Request $request)
    {
        Department::where('id', $request->id)->update(['status' => 0]);
        return "success";
    }
    
    public function activate(Request $request)
    {
        // dd($request->all());
        Department::where('id', $request->id)->update(['status' => 1]);
        return "success";
    }
}