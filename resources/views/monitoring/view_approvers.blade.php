<div class="modal" id="viewApprovers{{ $change_request->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">View approvers</h6>
            </div>
            <div class="modal-body">
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
                                <td>{{date("M d Y", strtotime($approver->start_date))}}</td>
                                <td>
                                    @if($approver->status == "Approved")
                                    {{date("M d Y", strtotime($approver->updated_at))}}
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