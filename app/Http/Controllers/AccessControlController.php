<?php

namespace App\Http\Controllers;

use App\RoleAccessControl;
use App\RolePermission;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AccessControlController extends Controller
{
    public function index()
    {
        $saved = [];
        $records = RolePermission::all();

        foreach ($records as $record) {
            $saved[$record->role][$record->permission_key] = [
                'view'    => $record->can_view,
                'create'  => $record->can_create,
                'edit'    => $record->can_edit,
                'approve' => $record->can_approve,
                'delete'  => $record->can_delete,
            ];
        }

        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get()->map(function ($role) {
            return [
                'id'    => str_replace(' ', '_', strtolower($role->name)),
                'label' => $role->name,
            ];
        })->toArray();

        $roles[] = [
            'id'    => 'user',
            'label' => 'User',
        ];

        return view('settings.access_control.index', compact('saved', 'roles'));
    }

    public function update(Request $request)
    {
        $role        = $request->get('role');
        $permissions = $request->get('permission', []);

        $groups  = $this->getGroups();
        $actions = ['view', 'create', 'edit', 'approve', 'delete'];

        foreach ($groups as $group) {
            foreach ($group['perms'] as $permKey => $permData) {
                RolePermission::updateOrCreate(
                    ['role' => $role, 'permission_key' => $permKey],
                    [
                        'can_view'    => isset($permissions[$permKey]['view'])    ? 'on' : null,
                        'can_create'  => isset($permissions[$permKey]['create'])  ? 'on' : null,
                        'can_edit'    => isset($permissions[$permKey]['edit'])    ? 'on' : null,
                        'can_approve' => isset($permissions[$permKey]['approve']) ? 'on' : null,
                        'can_delete'  => isset($permissions[$permKey]['delete'])  ? 'on' : null,
                    ]
                );
            }
        }

        Alert::success('Permissions updated successfully!')->persistent('Dismiss');
        return back();
    }

    private function getGroups()
    {
        return [
            'dashboard' => [
                'label'  => 'Dashboard',
                'icon'   => 'ri-dashboard-2-line',
                'single' => true,
                'perms'  => [
                    'dashboard' => ['Dashboard'],
                ],
            ],
            'change_request' => [
                'label'  => 'Change Requests',
                'icon'   => 'ri-edit-line',
                'single' => true,
                'perms'  => [
                    'change request' => ['Change Requests'],
                ],
            ],
            'for_approval' => [
                'label'  => 'For Approval',
                'icon'   => 'ri-checkbox-line',
                'single' => true,
                'perms'  => [
                    'for approval' => ['For Approval'],
                ],
            ],
            'documents' => [
                'label'  => 'Documents',
                'icon'   => 'ri-folder-2-line',
                'single' => true,
                'perms'  => [
                    'documents' => ['Documents'],
                ],
            ],
            'permits' => [
                'label'  => 'Permits & Licenses',
                'icon'   => 'ri-file-shield-line',
                'single' => true,
                'perms'  => [
                    'permits and license' => ['Permits & Licenses'],
                ],
            ],
            'approver_stamp' => [
                'label'  => 'Approver Stamp',
                'icon'   => 'mdi mdi-stamper',
                'single' => true,
                'perms'  => [
                    'approver stamp' => ['Approver Stamp'],
                ],
            ],
            'settings' => [
                'label'  => 'Settings',
                'icon'   => 'ri-settings-3-line',
                'single' => false,
                'perms'  => [
                    'department'           => ['Departments'],
                    'teams'                => ['Teams'],
                    'users'                => ['Users'],
                    'documents_type'       => ['Type of Documents'],
                    'roles and permission' => ['Roles & Permissions'],
                    'access_control'       => ['Access Control'],
                ],
            ],
        ];
    }
}