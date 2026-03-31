<div class="modal" id="viewApprovers{{ $change_request->id }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">Document Tracking - {{ $change_request->title }} - {{ $change_request->id }}</h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @php
                    use setasign\Fpdi\Fpdi;
                    $pdf = new Fpdi();
                    $page_count = 0;
                    if ($change_request->file) {
                        $page_count = $pdf->setSourceFile(public_path($change_request->file));
                    }
                    // dd($page_count);
                @endphp 
                <p><b>Total pages of documents:</b> {{$page_count}}</p>
                <p><b>Office:</b> {{optional($change_request->department)->name}}</p>
                <p><b>Number of supporting documents:</b> {{count($change_request->supporting_documents)}}</p>
                <div class="card" style="border: 1px solid #842029;">
                    <div class="card-header" style="background-color: #842029;">
                        <h6 class="card-title" style="color:white;">Description</h6>
                    </div>
                    <div class="card-body">
                        {{ $change_request->description }}
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>Name</th>
                            <th>Offices</th>
                            <th>Start Date</th>
                            <th>Transaction Date</th>
                            <th>Remarks</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($change_request->approvers as $approver)
                            <tr>
                                <td>{{$approver->level}}</td>
                                <td>{{$approver->user->name}}</td>
                                <td>
                                    {{ $approver->user->department->name ?? '—' }}
                                </td>
                                <td>{{date("M d Y h:i A", strtotime($approver->start_date))}}</td>
                                <td>
                                    @if($approver->status == "Approved")
                                        {{date("M d Y h:i A", strtotime($approver->updated_at))}}
                                    @endif
                                </td>
                                <td>{!! nl2br(e($approver->remarks)) !!}</td>
                                <td>
                                    @if($approver->status == "Pending")
                                        <span class="badge bg-warning">{{$approver->status}}</span>
                                    @elseif($approver->status == "Approved")
                                        <span class="badge bg-success">{{$approver->status}}</span>
                                    @else
                                        <span class="badge bg-danger">{{$approver->status}}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-danger mt-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success mt-2" data-bs-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>