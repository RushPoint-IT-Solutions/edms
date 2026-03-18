<div class="modal fade" id="share" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Share with others</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('/documents/share') }}" method="post" onsubmit="show()">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="" class="form-label">Documents</label>
                            <select name="documents" class="cat form-control" id="shareDocument" required>
                                <option value=""></option>
                                @foreach ($documents as $document)
                                <option value="{{ $document->id }}">{{$document->control_code." ".$document->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label">Users</label>
                            <select name="users[]" id="userMultiple" class="cat form-control" multiple required>
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        
                        <div id="peopleAccessContainer" style="display: none;">
                            <p class="fs-12 m-0 mb-2">People with access</p
                            {{-- <a href="javascript:void(0);" class="list-group-item list-group-item-action active">
                                <div class="d-flex mb-2 align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset("images/no_image.png") }}" alt="" class="avatar-sm rounded-circle" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="list-title fs-15 mb-1 text-dark" id="Name"></h5>
                                        <p class="list-text mb-0 fs-12 text-dark" id="Email"></p>
                                    </div>
                                </div>
                            </a> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>