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
        if (!canView('system_configuration.view')) {
            return view("pages.403-error");
        }

        $departments = Department::with('dep_head')->get();
        $employees = User::all();
        $offices = Department::orderBy('name')->get();

        $teams = Team::with('creator')->get();
        $activeDepartments = Department::where('status', 1)->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('system_configuration.index', compact(
            'departments',
            'employees',
            'teams',
            'activeDepartments',
            'documentTypes',
            'offices'
        ));
    }
}