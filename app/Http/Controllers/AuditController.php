<?php

namespace App\Http\Controllers;

use App\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $audits = Audit::get();
        return view(
            'audit',
            array(
                'audits' => $audits,
            )
        );
    }

    public function data(Request $request) {
        // dd($request->all());
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'];
        $order = $request->get('order')[0];
        $columnIndex = $order['column'];
        $columnName = $request->get('columns')[$columnIndex]['data'];
        $columnSortOrder = $order['dir'];
;
        $query = Audit::with('user')->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as timestamp"), "user_id", "event", "old_values", "new_values")
            ->when($request->date_filter, function($q) use($request) {
                $q->whereDate("created_at", $request->date_filter);
            })
            ->when($request->event_filter, function($q) use($request) {
                $q->where("event", $request->event_filter);
            })
            ->when($request->user_filter, function($q) use($request) {
                $q->where("user_id", $request->user_filter);
            });
            

        $totalRecords = Audit::count();

        // if (!empty($search)) {
        //     $query->where(function($q) use ($search) {
        //         $q->where('name', 'like', "%{$search}%")
        //           ->orWhere('email', 'like', "%{$search}%")
        //           ->orWhere('role', 'like', "%{$search}%")
        //           ->orWhereHas('company', function($q) use ($search) {
        //               $q->where('name', 'like', "%{$search}%");
        //           })
        //           ->orWhereHas('department', function($q) use ($search) {
        //               $q->where('name', 'like', "%{$search}%");
        //           });
        //     });
        // }

        // $roleFilter = $request->get('role_filter');
        // if (!empty($roleFilter)) {
        //     $query->where('role', $roleFilter);
        // }

        $totalFiltered = $query->count();

        $totalFiltered = $query->count();

        $columns = ["created_at", "user_id", "event", "old_values", "new_values"];
        if (in_array($columnName, $columns)) {
            $query->orderBy($columnName, $columnSortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }

        $activity_logs = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($activity_logs as $logs) {
            $data[] = [
                'created_at' => $logs->timestamp,
                'user_id' => $logs->user_id,
                'event' => $logs->event,
                'old_values' => $logs->old_values,
                'new_values' => $logs->new_values,
                'user' => optional($logs->user)->name
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
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
        //
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

    public function getEvents() {
        return events();
    }

    public function getActiveUsers() {
        return getActiveUser();
    }
}
