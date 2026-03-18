<?php

namespace App\Http\Controllers;

use App\ControlCode;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ControlCodeController extends Controller
{
    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = ControlCode::query();

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'action' => '
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button class="dropdown-item edit-control-code"
                                    data-id="' . $item->id . '"
                                    data-code="' . e($item->code) . '"
                                    data-description="' . e($item->description) . '">
                                    <i class="ri-pencil-line me-2"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item text-danger delete-control-code"
                                    data-id="' . $item->id . '"
                                    data-code="' . e($item->code) . '">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>',
                'code'        => '<strong>' . e($item->code) . '</strong>',
                'description' => $item->description ?? '<span class="text-muted">—</span>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|min:2|max:50|unique:control_codes,code',
            'description' => 'nullable|max:255',
        ]);

        ControlCode::create([
            'code' => strtoupper(trim($request->code)),
            'description' => $request->description,
        ]);

        Alert::success('Control code created successfully')->persistent('Dismiss');
        return back();
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