<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\Document;
use App\Department;
use App\RequestApprover;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        if(!canView("reports.view")) {
            return view("pages.403-error");
        }

        $departments = Department::where('status', null)->orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('reports.index', compact('departments', 'users'));
    }

    public function getSummaryStats(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        $query = ChangeRequest::whereNull('is_draft')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($to)->endOfDay()));

        $total = $query->count();
        $approved = $query->where('status', 'Approved')->count();
        $forApproval = $query->where('status', 'For Approval')->count();
        $declined = $query->where('status', 'Declined')->count();
        $returned = $query->where('status', 'Returned')->count();
        $draft = $query->where('status', 'Draft')->count();

        $year = date('Y');
        $monthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthly[] = $query->whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
        }

        $monthlyApproved = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyApproved[] = $query->where('status', 'Approved')
                ->whereYear('updated_at', $year)
                ->whereMonth('updated_at', $m)
                ->count();
        }

        $deptData = ChangeRequest::whereNull('is_draft')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($to)->endOfDay()))
            ->whereNotNull('department_id')
            ->selectRaw('department_id, count(*) as total')
            ->groupBy('department_id')
            ->orderByDesc('total')
            ->with('department')
            ->limit(6)
            ->get();

        $deptLabels = $deptData->map(fn($r) => optional($r->department)->code ?? optional($r->department)->name ?? 'N/A')->values()->toArray();
        $deptCounts = $deptData->pluck('total')->values()->toArray();

        return response()->json([
            'total' => $total,
            'approved' => $approved,
            'forApproval' => $forApproval,
            'declined' => $declined,
            'returned' => $returned,
            'draft' => $draft,
            'monthly' => $monthly,
            'monthlyApproved' => $monthlyApproved,
            'deptLabels' => $deptLabels,
            'deptCounts' => $deptCounts,
            'statusLabels' => ['Approved', 'For Approval', 'Declined', 'Returned', 'Draft'],
            'statusCounts' => [$approved, $forApproval, $declined, $returned, $draft],
        ]);
    }

    public function getChangeRequestsReport(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $from = $request->get('from');
        $to = $request->get('to');
        $status = $request->get('status');
        $departmentId = $request->get('department_id');
        $requestedBy = $request->get('requested_by');

        $query = ChangeRequest::with(['user.department', 'approvers.user'])
            ->whereNull('is_draft')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($to)->endOfDay()))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->when($requestedBy, fn($q) => $q->where('user_id', $requestedBy));

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $totalRecords = $query->count();
        $records = $query->orderBy('created_at', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($records as $cr) {
            $docId = 'DOC-' . date('Y', strtotime($cr->created_at)) . '-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT);

            switch ($cr->status) {
                case 'Approved': $badgeClass = 'bg-success'; break;
                case 'For Approval': $badgeClass = 'bg-primary'; break;
                case 'Declined': $badgeClass = 'bg-danger'; break;
                case 'Returned': $badgeClass = 'bg-warning text-dark'; break;
                default: $badgeClass = 'bg-secondary'; break;
            }

            $approversList = $cr->approvers->sortBy('level')
                ->map(fn($a) => optional($a->user)->name . ' (' . $a->status . ')')
                ->implode(' → ');

            $data[] = [
                'doc_id' => $docId,
                'title' => e($cr->title),
                'category' => e($cr->category ?? '—'),
                'department' => optional(optional($cr->user)->department)->name ?? '—',
                'requested_by' => optional($cr->user)->name ?? '—',
                'approvers' => $approversList ?: '—',
                'created_at' => $cr->created_at ? $cr->created_at->format('M d, Y') : '—',
                'updated_at' => $cr->updated_at ? $cr->updated_at->format('M d, Y') : '—',
                'status' => '<span class="badge ' . $badgeClass . '">' . e($cr->status) . '</span>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function getDocumentsReport(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $from = $request->get('from');
        $to = $request->get('to');
        $departmentId = $request->get('department_id');

        $query = Document::with(['user.department'])
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($to)->endOfDay()))
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId));

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('control_code', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $totalRecords = $query->count();
        $records = $query->orderBy('created_at', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($records as $doc) {
            $data[] = [
                'control_code' => e($doc->control_code ?? '—'),
                'title' => e($doc->title),
                'category' => e($doc->category ?? '—'),
                'department' => optional(optional($doc->user)->department)->name ?? '—',
                'uploaded_by' => optional($doc->user)->name ?? '—',
                'version' => $doc->version ?? 0,
                'date_approved' => $doc->date_approved ? Carbon::parse($doc->date_approved)->format('M d, Y') : '—',
                'created_at' => $doc->created_at ? $doc->created_at->format('M d, Y') : '—',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function getApproverActivityReport(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';
        $from = $request->get('from');
        $to = $request->get('to');
        $status = $request->get('status');
        $approverId = $request->get('approver_id');

        $query = RequestApprover::with(['user.department', 'change_request'])
            ->whereIn('status', ['Approved', 'Declined', 'Returned'])
            ->when($approverId, fn($q) => $q->where('user_id', $approverId))
            ->when($from, fn($q) => $q->whereDate('updated_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn($q) => $q->whereDate('updated_at', '<=', Carbon::parse($to)->endOfDay()))
            ->when($status, fn($q) => $q->where('status', $status));

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('change_request', fn($q2) => $q2->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$search}%"));
            });
        }

        $totalRecords = $query->count();
        $records = $query->orderBy('updated_at', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($records as $approver) {
            $cr = $approver->change_request;
            $docId = $cr
                ? 'DOC-' . date('Y', strtotime($cr->created_at)) . '-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT)
                : '—';

            switch ($approver->status) {
                case 'Approved': $badgeClass = 'bg-success'; break;
                case 'Declined': $badgeClass = 'bg-danger'; break;
                case 'Returned': $badgeClass = 'bg-warning text-dark'; break;
                default: $badgeClass = 'bg-secondary'; break;
            }

            $data[] = [
                'doc_id' => $docId,
                'document' => $cr ? e($cr->title) : '—',
                'approver' => optional($approver->user)->name ?? '—',
                'department' => optional(optional($approver->user)->department)->name ?? '—',
                'level' => $approver->level ?? '—',
                'action' => '<span class="badge ' . $badgeClass . '">' . e($approver->status) . '</span>',
                'date' => $approver->updated_at ? $approver->updated_at->format('M d, Y h:i A') : '—',
                'remarks' => e($approver->remarks ?? '—'),
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function getDepartmentSummaryReport(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start', 0);
        $length = $request->get('length', 25);
        $search = $request->get('search')['value'] ?? '';
        $from = $request->get('from');
        $to = $request->get('to');

        $deptQuery = Department::where('status', null)
            ->when(!empty($search), fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name');

        $totalRecords = $deptQuery->count();
        $departments = $deptQuery->skip((int)$start)->take((int)$length)->get();

        $deptIds = $departments->pluck('id');

        $counts = ChangeRequest::whereNull('is_draft')
            ->whereIn('department_id', $deptIds)
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', Carbon::parse($to)->endOfDay()))
            ->selectRaw('department_id, status, count(*) as total')
            ->groupBy('department_id', 'status')
            ->get()
            ->groupBy('department_id');

        $data = [];
        foreach ($departments as $dept) {
            $rows = $counts->get($dept->id, collect());
            $byStatus = $rows->pluck('total', 'status');

            $data[] = [
                'department' => e($dept->name),
                'code' => e($dept->code ?? '—'),
                'total' => $rows->sum('total'),
                'approved' => '<span class="badge bg-success">' . ($byStatus['Approved'] ?? 0) . '</span>',
                'for_approval' => '<span class="badge bg-primary">' . ($byStatus['For Approval'] ?? 0) . '</span>',
                'declined' => '<span class="badge bg-danger">' . ($byStatus['Declined'] ?? 0) . '</span>',
                'returned' => '<span class="badge bg-warning text-dark">' . ($byStatus['Returned'] ?? 0) . '</span>',
                'draft' => '<span class="badge bg-secondary">' . ($byStatus['Draft'] ?? 0) . '</span>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'change_requests');
        $from = $request->get('from');
        $to = $request->get('to');
        $status = $request->get('status');
        $deptId = $request->get('department_id');

        $filename = $type . '_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($type, $from, $to, $status, $deptId) {
            $file = fopen('php://output', 'w');

            if ($type === 'change_requests') {
                fputcsv($file, ['Doc ID', 'Title', 'Category', 'Department', 'Requested By', 'Approvers', 'Date Requested', 'Last Updated', 'Status']);
                ChangeRequest::with(['user.department', 'approvers.user'])
                    ->whereNull('is_draft')
                    ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                    ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
                    ->when($status, fn($q) => $q->where('status', $status))
                    ->when($deptId, fn($q) => $q->where('department_id', $deptId))
                    ->orderBy('created_at', 'desc')
                    ->chunk(200, function ($rows) use ($file) {
                        foreach ($rows as $cr) {
                            $docId = 'DOC-' . date('Y', strtotime($cr->created_at)) . '-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT);
                            $approvers = $cr->approvers->sortBy('level')->map(fn($a) => optional($a->user)->name . ' (' . $a->status . ')')->implode(' → ');
                            fputcsv($file, [
                                $docId, $cr->title, $cr->category ?? '—',
                                optional(optional($cr->user)->department)->name ?? '—',
                                optional($cr->user)->name ?? '—',
                                $approvers ?: '—',
                                $cr->created_at ? $cr->created_at->format('M d, Y') : '—',
                                $cr->updated_at ? $cr->updated_at->format('M d, Y') : '—',
                                $cr->status,
                            ]);
                        }
                    });

            } elseif ($type === 'documents') {
                fputcsv($file, ['Control Code', 'Title', 'Category', 'Department', 'Uploaded By', 'Version', 'Date Approved', 'Date Created']);
                Document::with(['user.department'])
                    ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                    ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
                    ->when($deptId, fn($q) => $q->where('department_id', $deptId))
                    ->orderBy('created_at', 'desc')
                    ->chunk(200, function ($rows) use ($file) {
                        foreach ($rows as $doc) {
                            fputcsv($file, [
                                $doc->control_code ?? '—', $doc->title, $doc->category ?? '—',
                                optional(optional($doc->user)->department)->name ?? '—',
                                optional($doc->user)->name ?? '—',
                                $doc->version ?? 0,
                                $doc->date_approved ? Carbon::parse($doc->date_approved)->format('M d, Y') : '—',
                                $doc->created_at ? $doc->created_at->format('M d, Y') : '—',
                            ]);
                        }
                    });

            } elseif ($type === 'approver_activity') {
                fputcsv($file, ['Doc ID', 'Document Title', 'Approver', 'Department', 'Level', 'Action', 'Date', 'Remarks']);
                RequestApprover::with(['user.department', 'change_request'])
                    ->whereIn('status', ['Approved', 'Declined', 'Returned'])
                    ->when($from, fn($q) => $q->whereDate('updated_at', '>=', $from))
                    ->when($to, fn($q) => $q->whereDate('updated_at', '<=', $to))
                    ->when($status, fn($q) => $q->where('status', $status))
                    ->orderBy('updated_at', 'desc')
                    ->chunk(200, function ($rows) use ($file) {
                        foreach ($rows as $a) {
                            $cr = $a->change_request;
                            $docId = $cr ? 'DOC-' . date('Y', strtotime($cr->created_at)) . '-' . str_pad($cr->id, 3, '0', STR_PAD_LEFT) : '—';
                            fputcsv($file, [
                                $docId, $cr ? $cr->title : '—',
                                optional($a->user)->name ?? '—',
                                optional(optional($a->user)->department)->name ?? '—',
                                $a->level ?? '—', $a->status,
                                $a->updated_at ? $a->updated_at->format('M d, Y h:i A') : '—',
                                $a->remarks ?? '—',
                            ]);
                        }
                    });

            } elseif ($type === 'department_summary') {
                fputcsv($file, ['Department', 'Code', 'Total', 'Approved', 'For Approval', 'Declined', 'Returned', 'Draft']);
                Department::where('status', null)->orderBy('name')->get()->each(function ($dept) use ($file, $from, $to) {
                    $query = ChangeRequest::whereNull('is_draft')
                        ->where('department_id', $dept->id)
                        ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
                        ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to));

                    $rows = $query->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

                    fputcsv($file, [
                        $dept->name, $dept->code ?? '—',
                        $rows->sum(),
                        $rows['Approved'] ?? 0,
                        $rows['For Approval'] ?? 0,
                        $rows['Declined'] ?? 0,
                        $rows['Returned'] ?? 0,
                        $rows['Draft'] ?? 0,
                    ]);
                });
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}