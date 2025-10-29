<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Department;
use App\UserDepartment;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('department', 'company')->get();
        $companies = Company::get();
        $departments = Department::get();
        $roles = $this->roles();
        return view('users', array(
            'users' => $users,
            'companies' => $companies,
            'departments' => $departments,
            'roles' => $roles,
        ));
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);


        $new_account = new User;
        $new_account->name = $request->name;
        $new_account->email = $request->email;
        $new_account->company_id = $request->company;
        $new_account->department_id = $request->department;
        $new_account->role = $request->role;
        $new_account->password = bcrypt($request->password);
        $new_account->save();
        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    public function changepassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|confirmed',
        ]);

        $user = User::where('id', $id)->first();
        $user->password = bcrypt($request->password);
        $user->save();
        Alert::success('Successfully Change Password')->persistent('Dismiss');
        return back();
    }
    public function deactivate_user(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->status = 1;
        $user->password = "";
        $user->save();

        return "success";
    }
    public function activate_user(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->status = null;
        $user->save();

        return "success";
    }
    public function edit_user(Request $request, $id)
    {

        $this->validate($request, [
            'email' => 'unique:users,email,' . $id,
        ]);

        $account = User::where('id', $id)->first();
        $account->name = $request->name;
        $account->email = $request->email;
        $account->company_id = $request->company;
        $account->department_id = $request->department;
        $account->role = $request->role;
        $account->save();

        $share_department = UserDepartment::where('user_id',$id)->delete();
        if($request->share_department)
        {
            foreach($request->share_department as $d)
            {
                $department = new UserDepartment;
                $department->user_id = $id;
                $department->department_id = $d;
                $department->created_by = auth()->user()->id;
                $department->save();
            }
        }
      

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
    public function roles()
    {
        $roles = [
            'User' => 'User',
            // 'Documents and Records Controller' => 'Documents and Records Controller',
            'Department Head' => 'Department Head',
            'Document Control Officer' => 'Document Control Officer',
            'Business Process Manager' => 'Business Process Manager',
            'Management Representative' => 'Management Representative',
            'Administrator' => 'Administrator',
        ];

        return $roles;
    }

    public function addUserFromWpro(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if ($user == null)
        {
            $users = new User;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->password = $request->password;
            $users->department_id = $request->department_id;
            $users->company_id = $request->company_id;
            $users->role = $request->role;
            $users->save();

            return response()->json(['message' => 'Successfully Saved']);
        }
        else
        {
            return response()->json(['message' => 'Error! The email is existing in our system']);
        }
        
    }
}
