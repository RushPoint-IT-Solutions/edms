<?php

namespace App\Http\Controllers;

use App\Campus;
use App\Department;
use App\DocumentType;
use App\Team;
use App\User;
use App\Tag;
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

        $teams = Team::where('status', 1)->get();
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

    public function getCampusData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $query = Campus::query();
        $totalRecords = (clone $query)->count();
        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }
        $totalFiltered = $query->count();
        $items = $query->orderBy('name')->skip($start)->take($length)->get();
        $data = [];

        $actions = "";
        foreach ($items as $campus) {
            $actions .= '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">';
                        if(canEdit('system_configuration.campus_edit')) {
                            $actions .= '
                                <li>
                                    <button class="dropdown-item edit-campus"
                                        data-id="' . $campus->id . '"
                                        data-name="' . e($campus->name) . '">
                                        <i class="ri-pencil-line me-2"></i>Edit
                                    </button>
                                </li>
                            ';
                        }
                        if (canDelete('system_configuration.campus_delete')) {
                            $actions .= '
                                <li>
                                    <button class="dropdown-item text-danger delete-campus"
                                        data-id="' . $campus->id . '"
                                        data-name="' . e($campus->name) . '">
                                        <i class="ri-delete-bin-line me-2"></i>Delete
                                    </button>
                                </li>
                            ';
                        }
            $actions .= '
                    </ul>
                </div>
            '; 

            $data[] = [
                'action' => $actions,
                'name' => '<strong>' . e($campus->name) . '</strong>',
            ];
        }
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getDepartmentsData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = Department::with('dep_head');

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%")
                  ->orWhereHas('dep_head', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('name')->skip($start)->take($length)->get();

        $data = [];
        $actions = "";
        foreach ($items as $department) {
            $depHead = optional($department->dep_head)->name ?? '<span class="text-muted">-</span>';

            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">';
                    if(canEdit("system_configuration.office_edit")) {
                        $actions .= '
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editDepartment' . $department->id . '">
                                    <i class="ri-pencil-line me-2"></i>Edit
                                </button>
                            </li>
                        ';
                    }
                    if (canDelete("system_configuration.office_delete")) {
                        $actions .= '
                            <li>
                                <button class="dropdown-item text-danger delete-department"
                                    data-id="' . $department->id . '"
                                    data-name="' . e($department->name) . '">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </button>
                            </li>
                        ';
                    }
            $actions .= '
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'code' => $department->code ?? '<span class="text-muted">-</span>',
                'name' => '<strong>' . $department->name . '</strong>',
                'department_head' => $depHead,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    // public function getTeamsData(Request $request)
    // {
    //     $draw = $request->get('draw');
    //     $start = $request->get('start');
    //     $length = $request->get('length');
    //     $search = $request->get('search')['value'] ?? '';
    //     $statusFilter = $request->get('status_filter');

    //     $query = Team::with(['creator', 'department']);

    //     $totalRecords = (clone $query)->count();

    //     if (!empty($statusFilter)) {
    //         $query->where('status', $statusFilter === 'Active' ? 1 : 0);
    //     }

    //     if (!empty($search)) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', "%$search%")
    //               ->orWhereHas('creator', function ($q2) use ($search) {
    //                   $q2->where('name', 'like', "%$search%");
    //               });
    //         });
    //     }

    //     $totalFiltered = $query->count();
    //     $items = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

    //     $data = [];
    //     foreach ($items as $team) {
    //         $createdBy = $team->creator
    //             ? $team->creator->name . '<br><small class="text-muted">' . $team->created_at->format('M d, Y') . '</small>'
    //             : 'Unknown';

    //         $department = optional($team->department)->name ?? '<span class="text-muted">-</span>';

    //         $actions = '
    //             <div class="dropdown">
    //                 <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
    //                     <i class="ri-more-2-fill"></i>
    //                 </button>
    //                 <ul class="dropdown-menu">
    //                     <li>
    //                         <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTeam' . $team->id . '">
    //                             <i class="ri-pencil-line me-2"></i>Edit
    //                         </button>
    //                     </li>
    //                     <li>
    //                         <button class="dropdown-item text-danger delete-team"
    //                             data-id="' . $team->id . '"
    //                             data-name="' . e($team->name) . '">
    //                             <i class="ri-delete-bin-line me-2"></i>Delete
    //                         </button>
    //                     </li>
    //                 </ul>
    //             </div>';

    //         $data[] = [
    //             'action' => $actions,
    //             'name' => '<strong>' . $team->name . '</strong>',
    //             'created_by' => $createdBy,
    //             'department' => $department,
    //             'campus' => $team->campus,
    //         ];
    //     }

    //     return response()->json([
    //         'draw' => intval($draw),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $totalFiltered,
    //         'data' => $data,
    //     ]);
    // }

    public function getDocumentTypesData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = DocumentType::query();

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('name')->skip($start)->take($length)->get();

        $data = [];
        $actions = "";
        foreach ($items as $docType) {
            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">';
                    if(canEdit("system_configuration.document_type_edit")) {
                        $actions.= '
                            <li>
                                <button class="dropdown-item edit-document-type"
                                    data-id="' . $docType->id . '"
                                    data-name="' . e($docType->name) . '"
                                    data-category="' . e($docType->category) . '">
                                    <i class="ri-pencil-line me-2"></i>Edit
                                </button>
                            </li>
                        ';
                    }
                    if(canDelete("system_configuration.document_type_delete")) {
                        $actions .= '
                            <li>
                                <button class="dropdown-item text-danger delete-document-type"
                                    data-id="' . $docType->id . '"
                                    data-name="' . e($docType->name) . '">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </button>
                            </li>
                        ';
                    }
            $actions .= '
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'name' => '<strong>' . $docType->name . '</strong>',
                'category' => $docType->category ?? '<span class="text-muted">-</span>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getControlCodesData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = \App\ControlCode::query();

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('code')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $controlCode) {
            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editControlCode' . $controlCode->id . '">
                                <i class="ri-pencil-line me-2"></i>Edit
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item text-danger delete-control-code"
                                data-id="' . $controlCode->id . '"
                                data-code="' . e($controlCode->code) . '">
                                <i class="ri-delete-bin-line me-2"></i>Delete
                            </button>
                        </li>
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'code' => '<strong>' . $controlCode->code . '</strong>',
                'description' => $controlCode->description ?? '<span class="text-muted">-</span>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getTagsData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $query = Tag::with('user');
        $totalRecords = (clone $query)->count();
        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }
        $totalFiltered = $query->count();
        $items = $query->orderBy('name')->skip($start)->take($length)->get();
        $data = [];
        $actions = "";
        foreach ($items as $tags) {
            $actions .= '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">';
                    if(canEdit("system_configuration.tags_edit")) {
                        $actions .= '
                            <li>
                                <button class="dropdown-item edit-tags"
                                    data-id="' . $tags->id . '"
                                    data-name="' . e($tags->name) . '">
                                    <i class="ri-pencil-line me-2"></i>Edit
                                </button>
                            </li>
                        ';
                    }
                    if(canDelete("system_configuration.tags_delete")) {
                        $actions .= '
                            <li>
                                <button class="dropdown-item text-danger delete-tags"
                                    data-id="' . $tags->id . '"
                                    data-name="' . e($tags->name) . '">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </button>
                            </li>
                        ';
                    }
            $actions .= '
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'name' => '<strong>' . e($tags->name) . '</strong>',
                'created_by' => '<strong>' . e($tags->user->name) . '</strong>',
            ];
        }
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
}