<?php

namespace App\Http\Controllers;

use App\Department;
use App\DocumentType;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemConfigurationController extends Controller
{

    public function index()
    {
        $departments = Department::with('dep_head')->get();
        $employees = User::all();

        $teams = Team::with('creator')->get();
        $activeDepartments = Department::where('status', 1)->get();

        return view('system_configuration.index', compact(
            'departments',
            'employees',
            'teams',
            'activeDepartments'
        ));
    }
}