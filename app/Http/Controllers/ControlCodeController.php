<?php

namespace App\Http\Controllers;

use App\ControlCode;
use App\Department;
use App\Teams;
use App\DocumentType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ControlCodeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:255',
        ]);

        $code = $this->generateCode($request->document_type_id, $request->department_id);

        $controlCode = new ControlCode;
        $controlCode->code = $code;
        $controlCode->description = $request->description;
        $controlCode->document_type_id = $request->document_type_id;
        $controlCode->department_id = $request->department_id;
        $controlCode->status = 1;
        $controlCode->save();

        Alert::success('Control Code Generated: ' . $code)->persistent('Dismiss');
        return back();
    }

    private function generateCode(int $documentTypeId, int $departmentId): string
    {
        $docType = DocumentType::findOrFail($documentTypeId);
        $department = Department::findOrFail($departmentId);
        $year = now()->year;

        $prefix = "MarSU-{$department->code}-{$docType->name}-{$year}";

        $count = ControlCode::where('code', 'like', "{$prefix}-%")->count();

        do {
            $count++;
            $candidate = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
        } while (ControlCode::where('code', $candidate)->exists());

        return $candidate;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|min:2|max:50|unique:control_codes,code,' . $id,
            'description' => 'nullable|max:255',
        ]);

        $controlCode = ControlCode::findOrFail($id);
        $controlCode->update([
            'code' => strtoupper(trim($request->code)),
            'description' => $request->description,
        ]);

        Alert::success('Control code updated successfully')->persistent('Dismiss');
        return back();
    }

    public function destroy($id)
    {
        ControlCode::findOrFail($id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}