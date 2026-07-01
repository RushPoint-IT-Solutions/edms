<?php

use App\Department;
use App\RolePermission;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EdmsAccountsAndRolesSeeder extends Seeder
{
    public function run()
    {
        $guard = 'web';
        $defaultPassword = 'Marsu2025!';

        $roles = [
            'Super Admin',
            'Admin',
            'RMU',
            'ICTU',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guard,
            ]);
        }

        $permissions = [
            'access_control',
            'access_control.view',
            'access_request.view',
            'approver_stamp.create',
            'approver_stamp.view',
            'dashboard',
            'department',
            'document_approvals.view',
            'documents',
            'documents_type',
            'files.create',
            'files.publish',
            'files.view',
            'files.view_request',
            'monitoring.view',
            'permits_and_license.change_type',
            'permits_and_license.create',
            'permits_and_license.upload',
            'permits_and_license.view',
            'personal.delete_folder',
            'personal.edit_folder',
            'personal.new_folder',
            'personal.share_with_others',
            'personal.upload_file',
            'personal.view',
            'reports.view',
            'roles.create',
            'roles.edit',
            'roles.view',
            'share_with_me.view',
            'share_with_others.view',
            'system_configuration.campus_create',
            'system_configuration.campus_delete',
            'system_configuration.campus_edit',
            'system_configuration.document_type_create',
            'system_configuration.document_type_delete',
            'system_configuration.document_type_edit',
            'system_configuration.office_create',
            'system_configuration.office_delete',
            'system_configuration.office_edit',
            'system_configuration.tags_create',
            'system_configuration.tags_delete',
            'system_configuration.tags_edit',
            'system_configuration.view',
            'teams',
            'users.create',
            'users.edit',
            'users.view',
        ];

        foreach ($permissions as $index => $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => $guard],
                ['order_by' => $index + 1]
            );
        }

        $allPermissions = Permission::pluck('name')->toArray();
        Role::findByName('Super Admin', $guard)->syncPermissions($allPermissions);
        Role::findByName('Admin', $guard)->syncPermissions($allPermissions);

        $rmuDepartment = Department::firstOrCreate(
            ['code' => 'RMU'],
            ['name' => 'Records Management Unit']
        );

        $ictuDepartment = Department::firstOrCreate(
            ['code' => 'ICTU'],
            ['name' => 'Information and Communications Technology Unit']
        );

        $users = [
            ['name' => 'Jennifer Tavas', 'email' => 'tavas.jennifer@marsu.edu.ph', 'role' => 'RMU', 'department_id' => $rmuDepartment->id],
            ['name' => 'Kimberly Jarabilo', 'email' => 'jarabilo.kimberly@marsu.edu.ph', 'role' => 'RMU', 'department_id' => $rmuDepartment->id],
            ['name' => 'Nowell Maac', 'email' => 'maac.nowell@marsu.edu.ph', 'role' => 'Admin', 'department_id' => $rmuDepartment->id],
            ['name' => 'Shirley S. Sigue', 'email' => 'sigue.shirley@marsu.edu.ph', 'role' => 'RMU', 'department_id' => $rmuDepartment->id],
            ['name' => 'Jethro Magcamit', 'email' => 'magcamit.jethro@marsu.edu.ph', 'role' => 'RMU', 'department_id' => $rmuDepartment->id],
            ['name' => 'Jyka Labrador', 'email' => 'labrador.jykamae@marsu.edu.ph', 'role' => 'RMU', 'department_id' => $rmuDepartment->id],
            ['name' => 'Alona Paez', 'email' => 'paez.alona@marsu.edu.ph', 'role' => 'RMU', 'department_id' => $rmuDepartment->id],
            ['name' => 'Wilbert Benedicto', 'email' => 'benedicto.wilbert@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
            ['name' => 'Joy Juson', 'email' => 'juson.joy@marsu.edu.ph', 'role' => 'Super Admin', 'department_id' => $ictuDepartment->id],
            ['name' => 'Hyzel Saguid', 'email' => 'saguid.hyzel@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
            ['name' => 'Reynaldo Lingon', 'email' => 'lingon.reynaldo@marsu.edu.ph', 'role' => 'Super Admin', 'department_id' => $ictuDepartment->id],
            ['name' => 'Erick Lopez', 'email' => 'lopez.erickjohn@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
            ['name' => 'Lawrence Diokno', 'email' => 'diokno.lawrence@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
            ['name' => 'Alvin Regencia', 'email' => 'regencia.alvin@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
            ['name' => 'Randy Lantita', 'email' => 'lantita.randy@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
            ['name' => 'Dennis Mansalapus', 'email' => 'mansalapus.dennis@marsu.edu.ph', 'role' => 'ICTU', 'department_id' => $ictuDepartment->id],
        ];

        foreach ($users as $data) {
            $user = User::firstOrNew(['email' => $data['email']]);
            $isNewUser = !$user->exists;

            $user->name = $data['name'];
            $user->department_id = $data['department_id'];
            $user->role = $data['role'];
            $user->status = null;
            $user->email_verified_at = $user->email_verified_at ?: now();

            if ($isNewUser || !$user->password) {
                $user->password = Hash::make($defaultPassword);
            }

            $user->save();
            $user->syncRoles([$data['role']]);

            if (in_array($data['role'], ['Super Admin', 'Admin'])) {
                $user->syncPermissions($allPermissions);
            }
        }

        $modulePermissionKeys = [
            'dashboard',
            'change request',
            'for approval',
            'documents',
            'permits and license',
            'approver stamp',
            'department',
            'teams',
            'users',
            'documents_type',
            'roles and permission',
            'access_control',
        ];

        foreach (['Super Admin', 'Admin'] as $roleName) {
            foreach ($modulePermissionKeys as $permissionKey) {
                RolePermission::updateOrCreate(
                    ['role' => $roleName, 'permission_key' => $permissionKey],
                    [
                        'can_view' => 'on',
                        'can_create' => 'on',
                        'can_edit' => 'on',
                        'can_approve' => 'on',
                        'can_delete' => 'on',
                    ]
                );
            }
        }
    }
}
