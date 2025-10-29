
<div class="modal" id="editUser{{$user->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" >Edit User</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
        
        <form method='post' action='edit-user/{{$user->id}}' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Name :
                            <input type="text" class="form-control-sm form-control "  value="{{$user->name}}" readonly  name="name" required/>
                        </div>
                        <div class='col-md-12'>
                            Email :
                            <input type="email" class="form-control-sm form-control "  value="{{$user->email}}" readonly  name="email" required/>
                        </div>
                        <div class='col-md-12'>
                            Company :
                            <select name='company' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value='{{$company->id}}' @if($user->company_id == $company->id) selected @endif>{{$company->name}} - {{$company->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Department :
                            <select name='department' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($departments as $dep)
                                    <option value='{{$dep->id}}' @if($user->department_id == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Share Department :
                            <select name='share_department[]' class='form-control-sm form-control cat' multiple >
                                <option value=""></option>
                                @foreach($departments->where('id','!=',$user->department_id) as $dep)
                                    <option value='{{$dep->id}}' @foreach($user->departments as $d ) @if($d->department_id == $dep->id) selected @endif @endforeach >{{$dep->code}} - {{$dep->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Role :
                            <select name='role' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($roles as $role)
                                
                                    <option value='{{$role}}' @if($user->role == $role) selected @endif>{{$role}}</option>
                                
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>