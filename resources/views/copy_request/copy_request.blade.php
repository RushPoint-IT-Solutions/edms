
<div class="modal" id="copyRequest" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Copy Request</h5>
                </div>
            </div>
            <form method='post' action='{{url('copy_request/store')}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                {{ csrf_field() }}

                <input type="hidden" class="form-control-sm form-control " name="id" value='{{$document->id}}'  />
                <input type="hidden" class="form-control-sm form-control " name="control_code" value='{{$document->control_code}}'  />
                <input type="hidden" class="form-control-sm form-control " name="title" value='{{$document->title}}'  />
                <input type="hidden" class="form-control-sm form-control " name="revision" value='{{$document->version}}'  />

                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th class="p-0">Control No.</th>
                            <th class="p-0">Document Title</th>
                            <th class="p-0">Revision No.</th>
                        </tr>
                        <tr>
                            <td class="p-0">{{ $document->control_code }}</td>
                            <td class="p-0">{{ $document->title }}</td>
                            <td class="p-0">{{ $document->version }}</td>
                        </tr>
                    </table>

                    <hr>

                    <div class='row'>
                        <div class='col-md-4'>
                            Type of Document :
                            <input type="text" class="form-control-sm form-control " name="type_of_document" value='E-Copy' readonly required/>
                            {{-- <select name='type_of_document' onchange='select_type(this.value);' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                <option value="Hard Copy" >Hard Copy</option>
                                <option value="E-Copy" >E-Copy</option>
                            </select> --}}
                        </div>
                        <div class='col-md-4' >
                            Number of Copy :
                            <input type="text" class="form-control-sm form-control " id='number_copy' name="name" value='1' readonly required/>
                        </div>
                        <div class='col-md-4' >
                            Date Needed :
                            <input type="date" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="date_needed" required/>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Purpose :
                            <textarea name='purpose'  rows="5" cols="100" charswidth="23" class="form-control-sm form-control " required></textarea>
                            
                        </div>
                    </div>
                    
                    <hr>

                    <div class='row'>
                        <div class='col-md-6'>
                            Requestor : {{auth()->user()->name}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function select_type(value)
    {
        if(value == "Hard Copy")
        {
            document.getElementById("number_copy").readOnly = false;
            document.getElementById("number_copy").value = "1";
        }
        else
        {
            document.getElementById("number_copy").readOnly = true;
            document.getElementById("number_copy").value = "1";
        }
    }
</script>
