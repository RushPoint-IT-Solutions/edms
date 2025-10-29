
<div class="modal" id="new_department" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Department</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='new-department' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                <div class="modal-body">
                    <div class='row'>
                        {{ csrf_field() }}
                        <div class='col-md-12'>
                            Department Code :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('code') }}"  name="code" required/>
                        </div>
                        <div class='col-md-12'>
                            Department Name :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('name') }}"  name="name" required/>
                        </div>
                        <div class='col-md-12'>
                            Department Head :
                            <select name='user_id' class='form-control-sm form-control cat' >
                                <option value=""></option>
                                @foreach($employees as $employee)
                                    <option value='{{$employee->id}}' @if(old('user_id') == $employee->id) selected @endif>{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Permits Accountable<i>(optional)</i> :
                            <select name='permit_id[]' class='form-control-sm form-control cat' multiple>
                                <option value=""></option>
                                @foreach($employees as $employee)
                                    <option value='{{$employee->id}}' @if(old('permit_id') == $employee->id) selected @endif>{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class='col-md-12 mb-2 '>
                            <hr >
                            Approvers <button type="button" onclick='add_approver()' class="btn btn-primary btn-xs"><i class='fa fa-plus-square-o'></i></button> <button type="button" onclick='remove_approver()' class="btn btn-danger btn-xs"><i class='fa fa-minus-square-o'></i></button>
                            <span>&nbsp;</span>
                            <div class='approvers-data form-group'>
                                <div class='row mb-2  mt-2 form-group ' id='approver_1'>
                                    <div class='col-md-1  text-right'>
                                    <small class='align-items-center'>1</small>
                                    </div>
                                    <div class='col-md-11'>
                                        <select name='approvers[]' class='form-control-sm form-control cat' required>
                                            <option value=""></option>
                                            @foreach($employees as $employee)
                                                <option value='{{$employee->id}}' @if(old('user_id') == $employee->id) selected @endif>{{$employee->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
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
<script>
  function add_approver()
  {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    var last_id = lastItemID.split("_");
        finalLastId = parseInt(last_id[1]) + 1;
                                 
        var item = "<div class='row mb-2  mt-2 form-group' id='approver_"+finalLastId+"'>";
            item+= "<div class='col-md-1  text-right'>";
            item+= "<small class='align-items-center'>"+finalLastId+"</small>";
            item+= "</div>";
            item+= " <div class='col-md-11'>";
            item+= " <select name='approvers[]' class='form-control-sm form-control cat' required>";
            item+= "<option value=''>-- Approver --</option>";
            item+= "@foreach($employees as $employee)";
            item+= "<option value='{{$employee->id}}' >{{$employee->name}}</option>";
            item+= "@endforeach";
            item+= "</select>";
            item+= "</div>";
            item+= "</div>";
          
            $(".approvers-data").append(item);
            $('.cat').chosen({width: "100%"});

  }
  function add_edit_approver(id_dept)
  {
    var lastItemID = $('.approvers-data-'+id_dept).children().last().attr('id');
    if(lastItemID)
    {
        var last_id = lastItemID.split("_");
        finalLastId = parseInt(last_id[2]) + 1;
    }
    else
    {
        var finalLastId = 1;
    }
 
                                 
        var item = "<div class='row mb-2  mt-2 form-group' id='approver_"+id_dept+"_"+finalLastId+"'>";
            item+= "<div class='col-md-1  text-right'>";
            item+= "<small class='align-items-center'>"+finalLastId+"</small>";
            item+= "</div>";
            item+= " <div class='col-md-11'>";
            item+= " <select name='edit_approvers[]' class='form-control-sm form-control cat' required>";
            item+= "<option value=''>-- Approver --</option>";
            item+= "@foreach($employees as $employee)";
            item+= "<option value='{{$employee->id}}' >{{$employee->name}}</option>";
            item+= "@endforeach";
            item+= "</select>";
            item+= "</div>";
            item+= "</div>";
          
            $(".approvers-data-"+id_dept).append(item);
            $('.cat').chosen({width: "100%"});

  }
  function remove_approver()
  {
    if($('div.approvers-data div.row').length > 1)
    {
    var lastItemID = $('.approvers-data').children().last().attr('id');
    $('#'+lastItemID).remove();
    }

  }

  function remove_edit_approver(id_dept)
  {
    if($('div.approvers-data-'+id_dept+' div.row').length > 1)
    {
    var lastItemID = $('.approvers-data-'+id_dept).children().last().attr('id');
    $('#'+lastItemID).remove();
    }

  }
</script>