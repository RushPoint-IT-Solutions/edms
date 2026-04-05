@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Access Control</h4>
        <p class="text-muted mb-0">Manage permissions</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex align-items-center gap-2 py-3">
        <i class="ri-shield-user-line fs-5"></i>
        <h5 class="mb-0">{{ $user->name }}</h5>
        <small class="text-muted ms-1">({{ $user->role }})</small>
    </div>
</div>

<form method="POST" action="{{ url('/users/access-control/update') }}" id="accessControlForm">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">

    <div class="row">
        @foreach ($permissions as $module => $action)
            @php
                $module_name = str_replace('_', ' ', strtoupper($module));
                // $checkView = $user->hasPermissionTo($action['view']);
                // if (isset($action['create'])) {
                //     $checkCreate = $user->hasPermissionTo($action['create']);
                // }
                // if(isset($action['edit'])) {
                //     $checkEdit   = $user->hasPermissionTo($action['edit']);
                // }
                // $checkDelete = $user->hasPermissionTo($action['delete']);
            @endphp
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <p class="card-title m-0"> {{ $module_name }} <small class="text-gray">Permission</small></p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Select all</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($action as $name => $id)
                            @php
                                $has_permission = $user->hasPermissionTo($id);
                            @endphp
                            <div class="col-lg-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" role="switch" name="permission[]" value="{{ $id }}" @if($has_permission) checked @endif>
                                    <label class="form-check-label">{{str_replace("_"," ",ucfirst($name))}}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-footer d-flex align-items-center justify-content-end gap-2">
            <a href="{{ url('users') }}" class="btn btn-danger btn-sm">Back</a>
            <button type="submit" class="btn btn-success btn-sm" id="SaveBtn">
                <i class="ri-save-line me-1"></i> Save Access
            </button>
        </div>
    </div>
</form>

@endsection

@section('js')
<script src="{{ asset('js/ajaxRequest.js') }}"></script>
<script>
$(document).ready(function () {
    $("#accessControlForm").on("submit", function (e) {
        e.preventDefault();

        var formData = $(this).serializeArray();

        ajaxRequest({
            type: "POST",
            url: "{{ url('/users/access-control/update') }}",
            data: formData,
            beforeSend: function () {
                $("#SaveBtn").prop("disabled", true).html('<i class="ri-loader-4-line me-1"></i> Saving...');
            },
            success: function (response) {
                if (response.status == "success") {
                    swal("Success", response.message, response.status);
                }
            },
            complete: function () {
                $("#SaveBtn").prop("disabled", false).html('<i class="ri-save-line me-1"></i> Save Access');
                location.reload();
            }
        });
    });

    $("#readAll").on("change", function () {
        $(".read-check").prop("checked", $(this).is(":checked"));
    });

    $("#createAll").on("change", function () {
        $(".create-check").prop("checked", $(this).is(":checked"));
    });
    $("#updateAll").on("change", function () {
        $(".update-check").prop("checked", $(this).is(":checked"));
    });
    $("#deleteAll").on("change", function () {
        $(".delete-check").prop("checked", $(this).is(":checked"));
    });

    function syncAllToggle(checkClass, allId) {
        var total   = $(checkClass).length;
        var checked = $(checkClass + ':checked').length;
        $(allId).prop('checked', total > 0 && total === checked);
    }

    syncAllToggle('.read-check',   '#readAll');
    // syncAllToggle('.create-check', '#createAll');
    // syncAllToggle('.update-check', '#updateAll');
    // syncAllToggle('.delete-check', '#deleteAll');

    $(document).on('change', '.read-check', function () { 
        syncAllToggle('.read-check',   '#readAll');   
    });

    // $(document).on('change', '.create-check', function () { 
    //     syncAllToggle('.create-check', '#createAll'); 
    // });

    // $(document).on('change', '.update-check', function () { 
    //     syncAllToggle('.update-check', '#updateAll'); 
    // });

    // $(document).on('change', '.delete-check', function () { 
    //     syncAllToggle('.delete-check', '#deleteAll'); 
    // });
});
</script>
@endsection