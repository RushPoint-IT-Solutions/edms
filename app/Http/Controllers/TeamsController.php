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
        $teams = Team::with('creator')->get();
        $totalTeams = $teams->count();
        $activeTeams = $teams->where('status', null)->count();
        $inactiveTeams = $totalTeams - $activeTeams;
        $departments = Department::whereNull('status')->get();
        
        return view('settings.teams.index', compact('teams', 'totalTeams', 'activeTeams', 'inactiveTeams','departments'));
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
                'created_by' => Auth::id(),
                'status' => null,
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
                'department_id' => $request->department
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

    public function deactivate(Request $request)
    {
        try {
            $team = Team::findOrFail($request->id);
            $team->update(['status' => 1]);

            return response()->json([
                'success' => true,
                'message' => 'Team deactivated successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Team deactivation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate team: ' . $e->getMessage()
            ], 500);
        }
    }

    public function activate(Request $request)
    {
        try {
            $team = Team::findOrFail($request->id);
            $team->update(['status' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Team activated successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Team activation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate team: ' . $e->getMessage()
            ], 500);
        }
    }

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