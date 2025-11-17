@extends('layouts.header')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                DOCUMENT INFORMATION
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">DOC ID: <strong>DOC-{{ date('Y', strtotime($change_request->created_at)) }}-{{ str_pad($change_request->id,3,'0',STR_PAD_LEFT) }}</strong></div>
                    <div class="col-md-12">Title: <strong>{{ $change_request->title }}</strong></div>
                    <div class="col-md-12">Description: <strong>{!! nl2br(e($change_request->description)) !!}</strong></div>
                    <div class="col-md-12">Category: <strong>{{ $change_request->category }}</strong></div>
                    <div class="col-md-12">Privacy: <strong>{{ $change_request->privacy }}</strong></div>
                    <div class="col-md-12">Revision: <strong>{{ $change_request->revision }}</strong></div>
                    <div class="col-md-12">Requested By: <strong>{{ $change_request->user->name }}</strong></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        ATTACHMENTS
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">File : 
                                <a href="{{ url($change_request->file) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                            </div>
                            <div class="col-md-12">Supporting Documents :
                                <br>
                                @foreach ($change_request->supporting_documents as $key=>$document)
                                    {{ $key+1 }}.
                                    <a href="{{ url($document->file) }}" target="_blank">
                                        <i class="fa fa-file"></i>
                                    </a>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        APPROVER
                    </div>
                    <div class="card-body">
                        @foreach ($change_request->approvers as $approver)
                            {{ $approver->user->name }} - {{ $approver->status }} <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Comments
            </div>
            <div class="card-body">
                <div class="row">
                    <form method="POST" action="{{ url('change-request/comments') }}" onsubmit="show()">
                        @csrf 
                        <input type="hidden" name="change_request_id" value="{{ $change_request->id }}">
                        <div class="col-md-12">
                            <textarea name="comment" class="form-control" cols="30" rows="10" placeholder="Write a comment"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100 mt-2">Comment</button>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ url('change-requests') }}" class="btn btn-danger w-100 mt-2">Cancel</a>
                        </div>
                    </form>
                    <hr class="mt-2">
                    @php
                        $request = ($change_request->approvers)->where('user_id', auth()->user()->id)->where('status', 'Pending');
                        $if_return = ($change_request->approvers)->whereIn('status', 'Returned');
                    @endphp
                    @if(count($request) > 0)
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success w-100 mt-2" data-bs-toggle="modal" data-bs-target="#approve{{ $change_request->id }}">Approved Documents</button>

                            @include('change_request.approve_modal')
                        </div>
                        
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#return{{ $change_request->id }}">Return Documents</button>

                            @include('change_request.return_modal')
                        </div>
                    @endif
                    @if(count($if_return) > 0 && (auth()->user()->id == $change_request->user_id))
                    <form action="{{ url('change-request/change-request-action/'.$change_request->id) }}" method="POST">
                        @csrf

                        <input type="hidden" name="action" value="Submit">

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success w-100 mt-2">Submit Documents</button>
                        </div>
                    </form>
                    @endif
                </div>
                <hr>
                <div class="row">
                    @if(count($change_request->comments) > 0)
                    @foreach ($change_request->comments as $comment)
                    <div class="col-md-12">
                        <strong>{{ $comment->user->name }}<span class="ms-2 text-muted"><i class="fa fa-clock-o"></i> {{ date('h:i A', strtotime($comment->created_at)) }} {{ date('M d Y', strtotime($comment->created_at)) }}</span></strong>
                        <div class="row">
                            <div class="col-md-12 my-2">{!! $comment->comment !!}</div>
                            {{-- <div class="col-md-12">Pending -> Returned</div> --}}
                        </div>
                    </div>
                    <hr class="m-2">
                    @endforeach
                    @else
                    <p style="font-style: italic;">No comment...</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection