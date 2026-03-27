<?php

namespace App\Http\Controllers;

use App\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function getCampusList()
    {
        $campuses = Campus::orderBy('name')->get(['id', 'name']);
        return response()->json($campuses);
    }

    public function storeCampus(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name'
        ]);

        Campus::create([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true, 'message' => 'Campus created successfully!'], 201);
    }

    public function updateCampus(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:campuses,name,' . $id
        ]);

        $campus = Campus::findOrFail($id);
        $campus->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true, 'message' => 'Campus updated successfully!'
        ]);
    }
    public function destroyCampus($id)
    {
        Campus::findOrFail($id)->delete();
        return response()->json([
            'success' => true, 'message' => 'Campus deleted successfully!'
        ]);
    }
}
