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
        return view('approver_stamp.index');
    }

    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        $query = ApproverStamp::with('user');

        $totalRecords = (clone $query)->count();

        if (!empty($search)) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('file_name', 'like', "%$search%");
        }

        $totalFiltered = $query->count();
        $items = $query->orderBy('id', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'file_name' => $item->file_name,
                'user' => $item->user->name ?? '-',
                'preview' => '<img src="' . url($item->file) . '" style="height:50px;object-fit:contain;">',
                'created_at' => $item->created_at ? $item->created_at->format('M d, Y') : '-',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
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
