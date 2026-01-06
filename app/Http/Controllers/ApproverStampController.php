<?php

namespace App\Http\Controllers;

use App\ApproverStamp;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ApproverStampController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approver = ApproverStamp::where('user_id', auth()->id())->first();

        return view('approver_stamp.index',
            array(
                'approver' => $approver
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
        $request->validate([
            'attachment' => 'required'
        ]);

        $approver_stamp = ApproverStamp::where('user_id', auth()->id())->first();
        if ($approver_stamp)
        {
            $attachment = $request->file('attachment');
            $name = time()."_".$attachment->getClientOriginalName();
            $attachment->move(public_path('approver_stamp'),$name);
            $file = "/approver_stamp/".$name;
            
            $approver_stamp->file = $file;
            $approver_stamp->file_name = $name;
            $approver_stamp->user_id = auth()->id();
            $approver_stamp->save();
        }
        else
        {
            $approver_stamp = new ApproverStamp;
    
            $attachment = $request->file('attachment');
            $name = time()."_".$attachment->getClientOriginalName();
            $attachment->move(public_path('approver_stamp'),$name);
            $file = "/approver_stamp/".$name;
            
            $approver_stamp->file = $file;
            $approver_stamp->file_name = $name;
            $approver_stamp->user_id = auth()->id();
            $approver_stamp->save();
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
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
}
