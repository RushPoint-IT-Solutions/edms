<?php

namespace App\Http\Controllers;

use App\ApproverStamp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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
                'preview' => '<a href="'.url($item->file).'"> <img src="' . url($item->file) . '" style="height:50px;object-fit:contain;"> </a>',
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'attachment' => 'required|mimes:png,jpg,jpeg'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        try {
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

                return response()->json(['status' => 'success', 'message' => 'Successfully Updated']);
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

                return response()->json(['status' => 'success', 'message' => 'Successfully Saved']);
            }
        } catch (\Exception $e) {
            Log::error("Error in upload stamp " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Something went wrong'], 500);
        }
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
