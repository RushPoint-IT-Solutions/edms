<?php

namespace App\Http\Controllers;

use App\DocumentType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TypeOfDocumentController extends Controller
{
    public function index()
    {
        $total = DocumentType::count();

        return view('settings.documents_type.index', [
            'total' => $total,
        ]);
    }

    public function getData(Request $request)
    {
        $draw   = $request->get('draw');
        $start  = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = DocumentType::query();

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        $types = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($types as $type) {
            $data[] = [
                'action' => '
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item edit-btn" href="#"
                                    data-id="' . $type->id . '"
                                    data-name="' . e($type->name) . '"
                                    data-category="' . e($type->category) . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDocumentTypeModal">
                                    <i class="ri-pencil-line me-2"></i>Edit
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger delete-btn" href="#"
                                    data-id="' . $type->id . '"
                                    data-name="' . e($type->name) . '">
                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                ',
                'name' => e($type->name),
                'category' => e($type->category),
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
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
        ]);

        $type = new DocumentType;
        $type->name = $request->name;
        $type->category = $request->category;
        $type->save();

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:document_types,name,' . $id,
            'category' => 'required|string|max:255',
        ]);

        $type = DocumentType::findOrFail($id);
        $type->name = $request->name;
        $type->category = $request->category;
        $type->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function destroy($id)
    {
        DocumentType::findOrFail($id)->delete();

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }
}