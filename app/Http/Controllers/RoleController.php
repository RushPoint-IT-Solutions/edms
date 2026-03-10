<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::get();
        $permissions = Permission::get();

        return view('roles.index', array(
            'roles' => $roles,
            'permissions' => $permissions,
            'totalRoles' => $roles->count(),
            'totalPermissions' => $permissions->count(),
        ));
    }

    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = Role::withCount('users');

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('name', 'asc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $role) {
            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit' . $role->id . '"><i class="ri-edit-box-line me-2"></i>Edit</button></li>
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view' . $role->id . '"><i class="ri-key-2-line me-2"></i>Permissions</button></li>
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'name' => $role->name,
                'users_count' => '<span class="badge bg-primary">' . $role->users_count . '</span>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getPermissionsData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = Permission::query();

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('name', 'asc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $permission) {
            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPermission' . $permission->id . '"><i class="ri-edit-box-line me-2"></i>Edit</button></li>
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'name' => $permission->name,
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
        // dd($request->all());
        $this->validate($request,[
            'name' => 'required'
        ]);

        $role = new Role;
        $role->name = $request->name;
        $role->guard_name = 'web';
        $role->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
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
        $this->validate($request,[
            'name' => 'required'
        ]);
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

    public function addPermission(Request $request,$id)
    {
        $role = Role::findById($id);
        $role = Role::findByName($role->name);
        $role->syncPermissions($request->permission);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
}
