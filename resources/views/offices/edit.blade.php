<div class="modal fade" id="edit{{ $office->id }}" tabindex="-1" aria-labelledby="newOfficeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new office</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action='{{ url('/offices/update/'.$office->id) }}' onsubmit='show();' class="form-horizontal">
                <div class="modal-body">
                    <div class='row g-3'>
                        {{ csrf_field() }}

                        <div class='col-md-12'>
                            <label class="form-label">Office Code :</label>
                            <input type="text" class="form-control"  value="{{ old('code', $office->code) }}"  name="code" required/>
                        </div>
                        <div class='col-md-12'>
                            <label class="form-label">Office Name :</label>
                            <input type="text" class="form-control"  value="{{ old('name', $office->name) }}"  name="name" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Update</button>
                </div>
            </form>
        </div>
    </div>
</div>