<div class="modal" id="viewApprovers{{ $change_request->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">View approvers</h6>
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
                <p><b>Number of pages:</b> {{$page_count}}</p>
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
                            <th>Start Date</th>
                            <th>Transaction Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($change_request->approvers as $approver)
                            <tr>
                                <td>{{$approver->level}}</td>
                                <td>{{$approver->user->name}}</td>
                                <td>{{date("M d Y h:i A", strtotime($approver->start_date))}}</td>
                                <td>
                                    @if($approver->status == "Approved")
                                    {{date("M d Y h:i A", strtotime($approver->updated_at))}}
                                    @endif
                                </td>
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
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>