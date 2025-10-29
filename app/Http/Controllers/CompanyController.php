<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

use RealRashid\SweetAlert\Facades\Alert;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::get();
        //
        return view(
            'companies',
            array(
                'companies' => $companies,
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
        //
        $this->validate($request, [
            'code' => 'required|min:2|max:50|unique:companies',
            'name' => 'required',
        ]);


        $company = new Company;
        $company->code = $request->code;
        $company->name = $request->name;
        $company->save();
        Alert::success('Successfully Store')->persistent('Dismiss');
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
        //
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
        // dd($request->all());
        $company = Company::where('id', $request->id)->first();
        $company->status = "deactivated";
        $company->save();

        return "success";
    }
    public function activate(Request $request)
    {
        // dd($request->all());
        $company = Company::where('id', $request->id)->first();
        $company->status = null;
        $company->save();

        return "success";
    }
}
