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

    <div class="card-body p-0">
        <form method="POST" action="{{ url('/users/access-control/update') }}" id="accessControlForm">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="table-responsive px-3">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Module / Permission</th>
                            <th class="text-center" width="80">Create</th>
                            <th class="text-center" width="80">Read</th>
                            <th class="text-center" width="80">Update</th>
                            <th class="text-center" width="80">Delete</th>
                        </tr>
                        {{-- All toggle row --}}
                        <tr class="table-secondary">
                            <td><strong>All</strong></td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="createAll">
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="readAll">
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="updateAll">
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="deleteAll">
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $module => $action)
                            @php
                                $checkView = $user->hasPermissionTo($action['view']);
                                // $checkCreate = $user->hasPermissionTo($action['create']);
                                // $checkEdit   = $user->hasPermissionTo($action['edit']);
                                // $checkDelete = $user->hasPermissionTo($action['delete']);
                            @endphp
                            <tr>
                                <td>
                                    <i class="ri-folder-line me-2 text-muted"></i>
                                    <strong>{{ str_replace('_', ' ', ucfirst($module)) }}</strong>
                                </td>

                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input create-check" type="checkbox" role="switch">
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if(isset($action['view']))
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input read-check"
                                               type="checkbox" role="switch"
                                               name="permission[]"
                                               value="{{ $action['view'] }}"
                                               {{ $action['view'] }}" @if($checkView) checked @endif>
                                    </div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input update-check" type="checkbox" role="switch">
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input delete-check" type="checkbox" role="switch">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-3 py-3 d-flex justify-content-end gap-2">
                <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">Cancel</a>
                <button type="submit" class="btn btn-success btn-sm" id="SaveBtn">
                    <i class="ri-save-line me-1"></i> Save Access
                </button>
            </div>
        </form>
    </div>
</div>

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
});
</script>
@endsection