<?php

namespace App\Http\Controllers;

use App\Office;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offices = Office::get();

        return view("offices.index",
            array(
                "offices" => $offices
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            "code" => "required|string|min:2|unique:offices,code",
            "name" => "required|string"
        ]);

        $office = new Office;
        $office->code = $request->code;
        $office->name = $request->name;
        $office->status = "Active";
        $office->save();

        Alert::success("Successfully Saved")->persistent("Dismiss");
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "code" => "required|string|min:2|unique:offices,code,".$id,
            "name" => "required|string"
        ]);

        $office = Office::findOrFail($id);
        $office->code = $request->code;
        $office->name = $request->name;
        $office->save();

        Alert::success("Successfully Updated")->persistent("Dismiss");
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deactivate(Request $request)
    {
        Office::where('id', $request->id)->update(['status' => 'Inactive']);
        return "success";
    }
    
    public function activate(Request $request)
    {
        // dd($request->all());
        Office::where('id', $request->id)->update(['status' => "Active"]);
        return "success";
    }
}
