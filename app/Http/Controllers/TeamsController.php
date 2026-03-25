<?php

namespace App\Http\Controllers;

use App\Department;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    public function index()
    {
        $totalTeams = Team::count();
        $activeTeams = Team::where('status', 1)->count();
        $inactiveTeams = Team::where('status', 0)->count();
        $departments = Department::where('status', '1')->get();
        $teams = Team::with('creator')->get();

        return view('settings.teams.index', compact('totalTeams', 'activeTeams', 'inactiveTeams', 'departments', 'teams'));
    }

    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $statusFilter = $request->get('status_filter');

        $query = Team::with(['creator', 'department']);

        $totalRecords = (clone $query)->count();

        if (!empty($statusFilter)) {
            if ($statusFilter === 'Active') {
                $query->where('status', 1);
            } else {
                $query->where('status', 0);
            }
        }

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhereHas('creator', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $team) {
            $createdBy = $team->creator
                ? $team->creator->name . '<br><small class="text-muted">' . $team->created_at->format('M d, Y') . '</small>'
                : 'Unknown';

            $department = optional($team->department)->name ?? '<span class="text-muted">-</span>';

            $actions = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTeam' . $team->id . '">
                                <i class="ri-pencil-line me-2"></i>Edit
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item text-danger delete-team"
                                data-id="' . $team->id . '"
                                data-name="' . e($team->name) . '">
                                <i class="ri-delete-bin-line me-2"></i>Delete
                            </button>
                        </li>
                    </ul>
                </div>';

            $data[] = [
                'action' => $actions,
                'name' => '<strong>' . $team->name . '</strong>',
                'created_by' => $createdBy,
                'department' => $department,
                'campus' => $team->campus,
            ];
        }

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'team_name' => 'required|string|max:255|unique:teams,name,NULL,id,deleted_at,NULL',
                'department' => 'required'
            ], [
                'team_name.required' => 'Team name is required',
                'team_name.unique' => 'A team with this name already exists',
                'team_name.max' => 'Team name cannot exceed 255 characters',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $team = Team::create([
                'name' => $request->team_name,
                'department_id' => $request->department,
                'campus' => $request->campus,
                'created_by' => Auth::id(),
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Team created successfully!',
                'team' => $team
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Team creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'team_name' => 'required|string|max:255|unique:teams,name,' . $id . ',id,deleted_at,NULL',
                'department' => 'required'
            ], [
                'team_name.required' => 'Team name is required',
                'team_name.unique' => 'A team with this name already exists',
                'team_name.max' => 'Team name cannot exceed 255 characters',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $team = Team::findOrFail($id);
            $team->update([
                'name' => $request->team_name,
                'department_id' => $request->department,
                'campus' => $request->campus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Team updated successfully!',
                'team' => $team
            ]);

        } catch (\Exception $e) {
            \Log::error('Team update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update team: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function deactivate(Request $request)
    // {
    //     try {
    //         $team = Team::findOrFail($request->id);
    //         $team->update(['status' => 0]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Team deactivated successfully!'
    //         ]);

    //     } catch (\Exception $e) {
    //         \Log::error('Team deactivation failed: ' . $e->getMessage());
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to deactivate team: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // public function activate(Request $request)
    // {
    //     try {
    //         $team = Team::findOrFail($request->id);
    //         $team->update(['status' => 1]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Team activated successfully!'
    //         ]);

    //     } catch (\Exception $e) {
    //         \Log::error('Team activation failed: ' . $e->getMessage());
            
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to activate team: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete();

            return response()->json([
                'success' => true,
                'message' => 'Team deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Team deletion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete team: ' . $e->getMessage()
            ], 500);
        }
    }
}