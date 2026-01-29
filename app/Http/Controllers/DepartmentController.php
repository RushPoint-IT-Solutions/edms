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
        $departments = Department::with([
            'dep_head:id,name',
            'approvers.user:id,name',
            'permit_accounts.user:id,name'
        ])->get();
        
        $employees = User::where('status', null)
            ->select('id', 'name', 'status')
            ->get();
            
        return view('departments.departments', 
            array(
                'departments' => $departments,
                'employees' => $employees,
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
        Department::where('id', $request->id)->update(['status' => 'deactivated']);
        return "success";
    }
    
    public function activate(Request $request)
    {
        // dd($request->all());
        Department::where('id', $request->id)->update(['status' => null]);
        return "success";
    }
}