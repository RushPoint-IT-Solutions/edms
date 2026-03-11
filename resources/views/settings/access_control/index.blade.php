@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Access Control</h4>
        <p class="text-muted mb-0">Manage role-based permissions</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            @foreach($roles as $role)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $role['id'] }}">
                    {{ $role['label'] }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="card-body tab-content">

        @php
            $groups = [
                'dashboard' => [
                    'label'  => 'Dashboard',
                    'icon'   => 'ri-dashboard-2-line',
                    'single' => true,
                    'perms'  => [
                        'dashboard' => ['Dashboard'],
                    ],
                ],
                'change_request' => [
                    'label'  => 'Change Requests',
                    'icon'   => 'ri-edit-line',
                    'single' => true,
                    'perms'  => [
                        'change request' => ['Change Requests'],
                    ],
                ],
                'for_approval' => [
                    'label'  => 'For Approval',
                    'icon'   => 'ri-checkbox-line',
                    'single' => true,
                    'perms'  => [
                        'for approval' => ['For Approval'],
                    ],
                ],
                'documents' => [
                    'label'  => 'Documents',
                    'icon'   => 'ri-folder-2-line',
                    'single' => true,
                    'perms'  => [
                        'documents' => ['Documents'],
                    ],
                ],
                'permits' => [
                    'label'  => 'Permits & Licenses',
                    'icon'   => 'ri-file-shield-line',
                    'single' => true,
                    'perms'  => [
                        'permits and license' => ['Permits & Licenses'],
                    ],
                ],
                'approver_stamp' => [
                    'label'  => 'Approver Stamp',
                    'icon'   => 'mdi mdi-stamper',
                    'single' => true,
                    'perms'  => [
                        'approver stamp' => ['Approver Stamp'],
                    ],
                ],
                'settings' => [
                    'label'  => 'Settings',
                    'icon'   => 'ri-settings-3-line',
                    'single' => false,
                    'perms'  => [
                        'department'           => ['Departments'],
                        'teams'                => ['Teams'],
                        'users'                => ['Users'],
                        'documents_type'       => ['Type of Documents'],
                        'roles and permission' => ['Roles & Permissions'],
                        'access_control'       => ['Access Control'],
                    ],
                ],
            ];

            $actions = ['view', 'create', 'edit', 'approve', 'delete'];
        @endphp

        @foreach($roles as $role)
        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $role['id'] }}">
            <form method="POST" action="{{ route('access-control.update') }}" onsubmit="show();">
                @csrf
                <input type="hidden" name="role" value="{{ $role['label'] }}">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Module / Permission</th>
                            @foreach($actions as $action)
                                <th class="text-center" width="80">{{ ucfirst($action) }}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <td><strong>All</strong></td>
                            @foreach($actions as $ai => $action)
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center mb-0">
                                        <input class="form-check-input all-toggle-{{ $role['id'] }}"
                                               type="checkbox"
                                               role="switch"
                                               id="all_{{ $action }}_{{ $role['id'] }}"
                                               data-action="{{ $ai }}"
                                               data-role="{{ $role['id'] }}">
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    </thead>

                    @foreach($groups as $groupKey => $group)
                        @php $safeGroupKey = str_replace(['.', '-'], '_', $groupKey); @endphp

                        @if($group['single'])
                            @foreach($group['perms'] as $permKey => $permData)
                                @php
                                    $label   = $permData[0];
                                    $safeKey = str_replace(['.', '-'], '_', $permKey);
                                    $dbRow   = $saved[$role['label']][$permKey] ?? null;
                                @endphp
                                <tbody>
                                    <tr>
                                        <td>
                                            <i class="{{ $group['icon'] }} me-2"></i>
                                            <strong>{{ $label }}</strong>
                                        </td>
                                        @foreach($actions as $ai => $action)
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                                    <input class="form-check-input perm-switch-{{ $role['id'] }} action-{{ $ai }}-{{ $role['id'] }}"
                                                           type="checkbox"
                                                           role="switch"
                                                           name="permission[{{ $permKey }}][{{ $action }}]"
                                                           id="{{ $role['id'] }}_{{ $safeKey }}_{{ $action }}"
                                                           data-role="{{ $role['id'] }}"
                                                           data-action="{{ $ai }}"
                                                           value="on"
                                                           {{ !empty($dbRow[$action]) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            @endforeach

                        @else
                            <tbody>
                                <tr class="table-secondary group-header-row"
                                    data-group-target="{{ $role['id'] }}-{{ $safeGroupKey }}"
                                    data-collapsed="true"
                                    style="cursor:pointer;user-select:none;">
                                    <td>
                                        <i class="{{ $group['icon'] }} me-2"></i>
                                        <strong>{{ $group['label'] }}</strong>
                                        <i class="ri-arrow-right-s-line ms-1 group-toggle-icon"></i>
                                    </td>
                                    @foreach($actions as $ai => $action)
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center mb-0">
                                                <input class="form-check-input group-toggle-{{ $role['id'] }}-{{ $safeGroupKey }}"
                                                       type="checkbox"
                                                       role="switch"
                                                       id="group_{{ $action }}_{{ $role['id'] }}_{{ $safeGroupKey }}"
                                                       data-action="{{ $ai }}"
                                                       data-role="{{ $role['id'] }}"
                                                       data-group="{{ $safeGroupKey }}"
                                                       onclick="event.stopPropagation();">
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>

                            <tbody class="group-child-wrapper" id="wrapper-{{ $role['id'] }}-{{ $safeGroupKey }}">
                                @foreach($group['perms'] as $permKey => $permData)
                                    @php
                                        $label   = $permData[0];
                                        $safeKey = str_replace(['.', '-'], '_', $permKey);
                                        $dbRow   = $saved[$role['label']][$permKey] ?? null;
                                    @endphp
                                    <tr class="group-child-row group-child-{{ $role['id'] }}-{{ $safeGroupKey }}">
                                        <td class="ps-4">
                                            <i class="ri-arrow-right-s-line text-muted me-1"></i>
                                            {{ $label }}
                                        </td>
                                        @foreach($actions as $ai => $action)
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                                    <input class="form-check-input perm-switch-{{ $role['id'] }} action-{{ $ai }}-{{ $role['id'] }} subperm-{{ $role['id'] }}-{{ $safeGroupKey }}-{{ $ai }}"
                                                           type="checkbox"
                                                           role="switch"
                                                           name="permission[{{ $permKey }}][{{ $action }}]"
                                                           id="{{ $role['id'] }}_{{ $safeKey }}_{{ $action }}"
                                                           data-role="{{ $role['id'] }}"
                                                           data-action="{{ $ai }}"
                                                           data-group="{{ $safeGroupKey }}"
                                                           value="on"
                                                           {{ !empty($dbRow[$action]) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    @endforeach
                </table>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-sm mt-2">Save</button>
                </div>
            </form>
        </div>
        @endforeach

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
<script>
$(document).ready(function () {

    function expandGroup(role, group) {
        const target   = role + "-" + group;
        const $header  = $("[data-group-target='" + target + "']");
        const $wrapper = $("#wrapper-" + target);
        const $rows    = $wrapper.find(".group-child-row");
        const isCollapsed = $header.data("collapsed") !== false && $header.data("collapsed") !== "false";
        if (isCollapsed) {
            $rows.each(function (i) {
                const $row = $(this);
                $row.css({ display: 'table-row', opacity: 0, transform: 'translateY(-8px)' });
                setTimeout(function () {
                    $row.css({ transition: 'opacity 0.25s ease, transform 0.25s ease', opacity: 1, transform: 'translateY(0)' });
                }, i * 40);
            });
            $wrapper.addClass("is-open");
            $header.data("collapsed", false).attr("data-collapsed", "false");
        }
    }

    function collapseGroup(role, group) {
        const target   = role + "-" + group;
        const $header  = $("[data-group-target='" + target + "']");
        const $wrapper = $("#wrapper-" + target);
        const $rows    = $wrapper.find(".group-child-row");
        const isCollapsed = $header.data("collapsed") !== false && $header.data("collapsed") !== "false";
        if (!isCollapsed) {
            $rows.each(function (i) {
                const $row = $(this);
                setTimeout(function () {
                    $row.css({ transition: 'opacity 0.2s ease, transform 0.2s ease', opacity: 0, transform: 'translateY(-8px)' });
                    setTimeout(function () { $row.css('display', 'none'); }, 200);
                }, i * 30);
            });
            $wrapper.removeClass("is-open");
            $header.data("collapsed", true).attr("data-collapsed", "true");
        }
    }

    $(document).on("click", ".group-header-row", function () {
        const target    = $(this).data("group-target");
        const collapsed = $(this).data("collapsed") !== false && $(this).data("collapsed") !== "false";
        const parts     = target.split("-");
        const role      = parts[0];
        const group     = parts.slice(1).join("-");
        if (collapsed) { expandGroup(role, group); } else { collapseGroup(role, group); }
    });

    $(document).on("change", "[id^='all_']", function () {
        const role    = $(this).data("role");
        const action  = $(this).data("action");
        const checked = this.checked;
        $(".action-" + action + "-" + role).prop("checked", checked);
        $("[id^='group_'][data-role='" + role + "'][data-action='" + action + "']").prop("checked", checked);
        const groups = new Set();
        $("[data-role='" + role + "'][data-group]").each(function () {
            const g = $(this).data("group");
            if (g) groups.add(g);
        });
        groups.forEach(function (group) {
            if (checked) {
                expandGroup(role, group);
            } else {
                const anyOn = $("[id^='group_'][data-role='" + role + "'][data-group='" + group + "']:checked").length > 0;
                if (!anyOn) collapseGroup(role, group);
            }
        });
        syncAllToggles(role);
    });

    $(document).on("change", "[id^=''][class*='perm-switch-']", function () {
        const role  = $(this).data("role");
        const group = $(this).data("group");
        syncAllToggles(role);
        if (group) syncGroupToggle(role, group);
    });

    $(document).on("change", "[id^='group_']", function () {
        const role    = $(this).data("role");
        const group   = $(this).data("group");
        const action  = $(this).data("action");
        const checked = this.checked;
        $(".subperm-" + role + "-" + group + "-" + action).prop("checked", checked);
        syncAllToggles(role);
        syncGroupToggle(role, group);
        const anyOn = $("[id^='group_'][data-role='" + role + "'][data-group='" + group + "']:checked").length > 0;
        if (anyOn) { expandGroup(role, group); } else { collapseGroup(role, group); }
    });

    function syncAllToggles(role) {
        [0, 1, 2, 3, 4].forEach(function (ai) {
            const col   = $(".action-" + ai + "-" + role);
            const allOn = col.length > 0 && col.length === col.filter(":checked").length;
            $("#all_" + ['view','create','edit','approve','delete'][ai] + "_" + role).prop("checked", allOn);
        });
    }

    function syncGroupToggle(role, group) {
        [0, 1, 2, 3, 4].forEach(function (ai) {
            const col   = $(".subperm-" + role + "-" + group + "-" + ai);
            const allOn = col.length > 0 && col.length === col.filter(":checked").length;
            $("#group_" + ['view','create','edit','approve','delete'][ai] + "_" + role + "_" + group).prop("checked", allOn);
        });
    }

    function syncGroupToggles(role) {
        const groups = new Set();
        $(".perm-switch-" + role + "[data-group]").each(function () {
            groups.add($(this).data("group"));
        });
        groups.forEach(function (g) { syncGroupToggle(role, g); });
    }

    function autoExpandCheckedGroups(role) {
        const groups = new Set();
        $(".perm-switch-" + role + "[data-group]:checked").each(function () {
            groups.add($(this).data("group"));
        });
        groups.forEach(function (group) {
            const target   = role + "-" + group;
            const $header  = $("[data-group-target='" + target + "']");
            const $wrapper = $("#wrapper-" + target);
            const $rows    = $wrapper.find(".group-child-row");
            $rows.css({ display: 'table-row', opacity: 1, transform: 'translateY(0)' });
            $wrapper.addClass("is-open");
            $header.data("collapsed", false).attr("data-collapsed", "false");
        });
    }

    @foreach($roles as $role)
    syncGroupToggles('{{ $role['id'] }}');
    syncAllToggles('{{ $role['id'] }}');
    autoExpandCheckedGroups('{{ $role['id'] }}');
    @endforeach

});
</script>
@endsection