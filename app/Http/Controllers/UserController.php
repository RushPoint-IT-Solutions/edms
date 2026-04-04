<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Department;
use App\UserDepartment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        if (!canView('users.view')) {
            return view("pages.403-error");
        }

        $companies = Company::get();
        $departments = Department::get();
        $roles = Role::get();
        
        $totalUsers = User::count();
        $activeUsers = User::where('status', '')->orWhereNull('status')->count();
        $inactiveUsers = User::where('status', '1')->count();
        $ssoUsers = User::whereNotNull('google_id', '')->count();
        
        return view('users.users', array(
            'companies' => $companies,
            'departments' => $departments,
            'roles' => $roles,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'ssoUsers' => $ssoUsers,
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

        // $query = User::select("name","email","department_id","role","status","id")->with(['department', 'company', 'departments.dep']);
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

        $roleFilter = $request->get('role_filter');
        if (!empty($roleFilter)) {
            $query->where('role', $roleFilter);
        }

        $totalFiltered = $query->count();

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
                ? '<span class="badge-status inactive badge bg-danger">Inactive</span>' 
                : '<span class="badge-status active badge bg-success">Active</span>';

            $actions = "";
            if(canEdit('users.edit') || canView('access_control.view')) {
                $actions = '<div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">';
                
                if ($user->status) {
                    if(canEdit('users.edit')) {
                        $actions .= '<li><button class="dropdown-item activate-user" data-id="'.$user->id.'">
                            <i class="ri-check-line me-2"></i>Activate</button></li>';
                    }
                } else {
                    if(canEdit('users.edit')) {
                        $actions .= '<li><button class="dropdown-item change-pass" data-bs-toggle="modal" data-bs-target="#change_pass'.$user->id.'" data-id='.$user->id.'>
                            <i class="ri-key-line me-2"></i>Change Password</button></li>';
                    }
                    if(canView('access_control.view')) {
                        $actions .= '<li>
                                        <a href="' . url('/users/access-control/'.$user->id) . '" class="dropdown-item" id="accessControlBtn'.$user->id.'">
                                            <i class="ri-user-settings-line"></i> Access Control
                                        </a>
                                    </li>';
                    }
                    if(canEdit('users.edit')) {
                        $actions .= '<li><button class="dropdown-item edit" type="button" id="editUserBtn">
                            <i class="ri-pencil-line me-2"></i>Edit</button></li>';
                    }
                    if(canEdit('users.edit')) {
                        if(auth()->user()->id != $user->id) {
                            $actions .= '<li><button class="dropdown-item deactivate-user" data-id="'.$user->id.'">
                                <i class="ri-close-line me-2"></i>Deactivate</button></li>';
                        }
                    }
                }
                
                $actions .= '</ul></div>';
            }

            $data[] = [
                'name' => ($user->name ?? 'N/A'),
                'email' => $user->email,
                // 'company' => $user->company->name ?? 'N/A',
                'department' => $user->department->name ?? '–',
                'department_id' => $user->department_id,
                // 'share_department' => '<div class="dept-list">' . $shareDepartments . '</div>',
                'role' => $user->role,
                'status' => $statusBadge,
                'action' => $actions,
                'user_id' => $user->id,
                "google_id" => $user->google_id
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    // public function getUserModals(Request $request)
    // {
    //     $userIds = $request->user_ids;
    //     $users = User::with(['department', 'company', 'departments.dep'])
    //         ->whereIn('id', $userIds)
    //         ->get();
        
    //     $companies = Company::get();
    //     $departments = Department::get();
    //     $roles = $this->roles();
        
    //     return view('partials.user_modals', compact('users', 'companies', 'departments', 'roles'));
    // }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'role' => 'required'
        ], [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already registered',
            'role.required' => 'Role is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $new_account = new User;
            $new_account->name = $request->name;
            $new_account->email = $request->email;
            $new_account->company_id = $request->company;
            $new_account->department_id = $request->department;
            $new_account->role = $request->role;
            $new_account->password = bcrypt('Marsu2025!');
            $new_account->role = $request->role;
            $new_account->save();

            return response()->json(['status' => "success", 'message' => 'Account created successfully!'], 201);
        } catch (\Exception $e) {
            Log::error("Error in creating user ", $e->getMessage());
        }
    }

    public function edit_user(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'role' => 'required|exists:roles,name',
            'department' => 'required|exists:departments,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        try {
            $account = User::where('id', $request->id)->first();
            $account->role = $request->role;
            $account->department_id = $request->department;
            $account->save();
            
            return response()->json(['status' => "success", 'message' => 'Account updated successfully!'], 201);
        } catch (\Exception $e) {
            Log::error("Error in updating user ", $e->getMessage());
        }
    }
    
    public function changepassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'password' => 'string|required|confirmed|min:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['status' => 'success', 'message' => 'Successfully Changed']);
        }
        catch (\Exception $e) {
            Log::error("Error changing password ", $e->getMessage());
        }
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

    public function accessControl(Request $request,$id)
    {
        $user = User::findOrFail($id);

        $permissions = [];
        $access_control = Permission::select(
            "id",
            "name",
            DB::raw("SUBSTRING_INDEX(name,'.',1) as Module"),
            DB::raw("SUBSTRING_INDEX(name,'.',-1) as Action"),
        )
        ->orderBy('order_by', 'asc')
        ->get();

        $permissions=[];
        foreach($access_control as $control) {
            $permissions[$control->Module][$control->Action] = $control->id;
        }

        return view("settings.access_control.index",
            array(
                'user' => $user,
                'permissions' => $permissions
            )   
        );
    }

    public function updateAccessControl(Request $request)
    {
        // dd($request->all());
        try {
            $user = User::findOrFail($request->user_id);
            if ($request->has('permission')) {
                $permissions = Permission::select("name")->whereIn("id", $request->permission)->get()->pluck("name")->toArray();
                $user->syncPermissions($permissions);
    
                return response()->json(['status' => 'success', 'message' => 'Successfully Saved']);
            }
            else {
                $user->syncPermissions([]);
                return response()->json(['status' => 'success', 'message' => 'Successfully Saved']);
            }
        } catch (\Exception $e) {
            Log::error("Error in access control ". $e->getMessage());
            return response()->json(['status' => 'success', 'errorInfo' => $e->getMessage()]);
        }
    }
}
