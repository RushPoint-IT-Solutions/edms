<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class TagController extends Controller
{
    public function getList()
    {
        $tags = Tag::with('user')->orderBy('name')->get(['id', 'name', 'created_by']);
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|unique:tags,name",
            "created_by" => "nullable|string"
        ]);

        $new_tag = new Tag;
        $new_tag->name = $request->name;
        $new_tag->created_by = auth()->user()->id;
        $new_tag->save();

        Alert::success("Successfully Saved")->persistent("Dismiss");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true, 'message' => 'Tag updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();
            \DB::commit();
            Alert::success('Successfully Deleted')->persistent('Dismiss');
        } catch (\Exception $e) {
            \DB::rollback();
            Alert::error('Error occurred')->persistent('Dismiss');
        }
        return back();
    }
}
