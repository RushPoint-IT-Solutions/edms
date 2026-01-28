<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Department;
use App\UserDepartment;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $companies = Company::get();
        $departments = Department::get();
        $roles = Role::get();
        
        $totalUsers = User::count();
        $activeUsers = User::where('status', '')->orWhereNull('status')->count();
        $inactiveUsers = User::where('status', '1')->count();
        
        return view('users.users', array(
            'companies' => $companies,
            'departments' => $departments,
            'roles' => $roles,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
        ));
    }

    public function getUsersData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0];
        $columnIndex = $order['column'];
        $columnName = $request->get('columns')[$columnIndex]['data'];
        $columnSortOrder = $order['dir'];

        $query = User::with(['department', 'company', 'departments.dep']);

        $totalRecords = User::count();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhereHas('company', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('department', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $totalFiltered = $query->count();

        $columns = ['name', 'email', 'role', 'status'];
        if (in_array($columnName, $columns)) {
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }

        $users = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($users as $user) {
            $statusBadge = $user->status 
                ? '<span class="badge-status inactive">Inactive</span>' 
                : '<span class="badge-status active">Active</span>';

            // $shareDepartments = '';
            // foreach($user->departments as $department) {
            //     $shareDepartments .= ($department->dep->name ?? 'N/A') . '<br>';
            // }

            $actions = '<div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                    <i class="ri-more-2-fill"></i>
                </button>
                <ul class="dropdown-menu">';
            
            if ($user->status) {
                $actions .= '<li><button class="dropdown-item activate-user" data-id="'.$user->id.'">
                    <i class="ri-check-line me-2"></i>Activate</button></li>';
            } else {
                $actions .= '<li><button class="dropdown-item change-pass" data-bs-toggle="modal" data-bs-target="#change_pass'.$user->id.'">
                    <i class="ri-key-line me-2"></i>Change Password</button></li>';
                $actions .= '<li><button class="dropdown-item edit" type="button" id="editUserBtn">
                    <i class="ri-pencil-line me-2"></i>Edit</button></li>';
                
                if(auth()->user()->id != $user->id) {
                    $actions .= '<li><button class="dropdown-item deactivate-user" data-id="'.$user->id.'">
                        <i class="ri-close-line me-2"></i>Deactivate</button></li>';
                }
            }
            
            $actions .= '</ul></div>';

            $data[] = [
                'name' => ($user->name ?? 'N/A'),
                'email' => $user->email,
                // 'company' => $user->company->name ?? 'N/A',
                'department' => $user->department->name ?? '–',
                // 'share_department' => '<div class="dept-list">' . $shareDepartments . '</div>',
                'role' => $user->role,
                'status' => $statusBadge,
                'action' => $actions,
                'user_id' => $user->id,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    public function getUserModals(Request $request)
    {
        $userIds = $request->user_ids;
        $users = User::with(['department', 'company', 'departments.dep'])
            ->whereIn('id', $userIds)
            ->get();
        
        $companies = Company::get();
        $departments = Department::get();
        $roles = $this->roles();
        
        return view('partials.user_modals', compact('users', 'companies', 'departments', 'roles'));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50',
            'email' => 'email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required'
        ]);


        $new_account = new User;
        $new_account->name = $request->name;
        $new_account->email = $request->email;
        $new_account->company_id = $request->company;
        $new_account->department_id = $request->department;
        $new_account->role = $request->role;
        $new_account->password = bcrypt($request->password);
        $new_account->save();

        $new_account->syncRoles($request->account);

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }
    public function changepassword(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'password' => 'required|confirmed|min:5',
        ]);

        $user = User::findOrFail($request->user_id);
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
    public function edit_user(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'email' => 'unique:users,email,' . $request->id,
        ]);

        $account = User::where('id', $request->id)->first();
        $account->name = $request->name;
        $account->email = $request->email;
        $account->role = $request->role;
        // $account->company_id = $request->company;
        // $account->department_id = $request->department;
        $account->save();
        
        $account->syncRoles($request->role);
        // $share_department = UserDepartment::where('user_id',$id)->delete();
        // if($request->share_department)
        // {
        //     foreach($request->share_department as $d)
        //     {
        //         $department = new UserDepartment;
        //         $department->user_id = $id;
        //         $department->department_id = $d;
        //         $department->created_by = auth()->user()->id;
        //         $department->save();
        //     }
        // }

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

    // public function addUserFromWpro(Request $request)
    // {
    //     $user = User::where('email', $request->email)->first();
        
    //     if ($user == null)
    //     {
    //         $users = new User;
    //         $users->name = $request->name;
    //         $users->email = $request->email;
    //         $users->password = $request->password;
    //         $users->department_id = $request->department_id;
    //         $users->company_id = $request->company_id;
    //         $users->role = $request->role;
    //         $users->save();

    //         return response()->json(['message' => 'Successfully Saved']);
    //     }
    //     else
    //     {
    //         return response()->json(['message' => 'Error! The email is existing in our system']);
    //     }
        
    // }
}
