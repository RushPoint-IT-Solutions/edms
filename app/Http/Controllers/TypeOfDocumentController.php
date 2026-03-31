<?php

namespace App\Http\Controllers;

use App\DocumentType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TypeOfDocumentController extends Controller
{
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