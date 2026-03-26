@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Access Control</h4>
        <p class="text-muted mb-0">Manage permissions</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>{{ $user->name }}</h4>
    </div>
    <div class="card-body tab-content">
        <form method="POST" action="{{ url("/users/access-control/update") }}" id="accessControlForm">
            @csrf

            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>Module</td>
                        <td>Create</td>
                        <td>
                            Read
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="readAll">
                            </div>
                        </td>
                        <td>Update</td>
                        <td>Delete</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $module => $action)
                        @php
                            // $checkCreate = $user->hasPermissionTo($action['create']);
                            $checkView = $user->hasPermissionTo($action['view']);
                            // $checkEdit = $user->hasPermissionTo($action['edit']);
                            // $checkDelete = $user->hasPermissionTo($action['delete']);
                        @endphp
                        <tr>
                            <td>{{str_replace("_"," ",ucfirst($module))}}</td>
                            <td>
                                {{-- @if(isset($action['create']))
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $action['create'] }}">
                                </div>
                                @endif --}}
                            </td>
                            <td>
                                @if(isset($action['view']))
                                <div class="form-check mb-2">
                                    <input class="form-check-input read-check" type="checkbox" name="permission[]" value="{{ $action['view'] }}" @if($checkView) checked @endif>
                                </div>
                                @endif
                            </td>
                            <td>
                                {{-- <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="">
                                </div> --}}
                            </td>
                            <td>
                                {{-- <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="">
                                </div> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-sm mt-2" id="SaveBtn">Save</button>
            </div>
        </form>
    </div>
</div>

<style>
    .group-header-row:hover { background-color: #d6d8db !important; }
    .group-toggle-icon {
        display: inline-block;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .group-header-row[data-collapsed="false"] .group-toggle-icon {
        transform: rotate(90deg);
    }
    .group-child-wrapper .group-child-row {
        display: none;
        opacity: 0;
        transform: translateY(-6px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .group-child-wrapper.is-open .group-child-row {
        display: table-row;
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection

@section('js')
<script src="{{ asset("js/ajaxRequest.js") }}"></script>
<script>
$(document).ready(function () {
    $("#accessControlForm").on("submit", function(e) {
        e.preventDefault()

        var formData = $(this).serializeArray()

        ajaxRequest({
            type:"POST",
            url: "{{ url('/users/access-control/update') }}",
            data: formData,
            beforeSend: function() {
                $("#SaveBtn").prop("disabled", true).text('Saving...')
            },
            success: function(response) {
                if (response.status == "success") {
                    swal("Successs", response.message, response.status)
                }
            },
            complete: function() {
                $("#SaveBtn").prop("disabled", false).text('Save')
                location.reload()
            }
        })
    })

    $("#readAll").on("click", function() {
        var checked = $(this).is(":checked")

        if (checked) {
            $(".read-check").prop("checked", true)
        }
        else {
            $(".read-check").prop("checked", false)
        }
    })
});
</script>
@endsection