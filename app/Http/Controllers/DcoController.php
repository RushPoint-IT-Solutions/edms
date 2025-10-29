<?php

namespace App\Http\Controllers;
use App\User;
use App\Department;
use App\DepartmentDco;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DcoController extends Controller
{
    //

    public function index()
    {
        
        $users = User::with('department', 'company','dco')->where('role','Document Control Officer')->get();
        $departments = Department::with('dco')->withCount('dco')->get();
        // dd($departments);
        return view('dco', array(
            'users' => $users,
            'departments' => $departments,
        ));
    }
    public function update(Request $request,$id)
    {
        $dcos = DepartmentDco::where('user_id',$id)->delete();

        foreach($request->department as $department)
        {
            $dco = new DepartmentDco;
            $dco->user_id = $id;
            $dco->department_id = $department;
            $dco->save();
        }
        
        Alert::success('Successfully Assigned')->persistent('Dismiss');
        return back();
    }
}
