@extends('layouts.app')

@section('content')

<style>
    .checkbox-dropdown {
        position: relative;
        width: 100%;
        user-select: none;
    }

    .checkbox-dropdown button {
        width: 100%;
        text-align: left;
    }

    .checkbox-list {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-height: 250px;
        overflow-y: auto;
        display: none;
        z-index: 9999;
    }

    .search-box {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .search-box input {
        width: 100%;
        padding: 7px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
</style>

<div class="container mt-4">

    <!-- ROLE -->
    <div class="mb-3">
        <label class="fw-bold mb-2">Role</label>
        <select id="roleSelect" class="form-control py-2 rounded-3 shadow-sm" name="role_name">
            <option selected>Please select role</option>
            @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- MODULE MULTI SELECT -->
    <div class="mb-3">
        <label class="fw-bold mb-2">Modules</label>

       <div id="moduleDropdown" class="dropdown">
    <button 
        id="moduleDropdownBtn" 
        class="btn btn-light border shadow-sm w-100 text-start" 
        data-bs-toggle="dropdown"
        data-bs-auto-close="false"
    >
        Select Modules
    </button>

    <ul class="dropdown-menu w-100 p-3" style="max-height: 300px; overflow-y:auto;">

        <!-- Search -->
        <li class="mb-2">
            <input type="text" id="moduleSearch" class="form-control" placeholder="Search module...">
        </li>

        <!-- MODULE LIST (FIXED) -->
        <li id="moduleList" class="p-0">
            @foreach($modules as $key => $module)
            <label class="d-block px-2 py-1">
                <input type="checkbox" class="moduleChk me-2" name="modules[]" value="{{ $key }}">
                {{ $module }}
            </label>
            @endforeach
        </li>

    </ul>
</div>


    <!-- TABLE -->
    <div id="tableSection" class="mt-4" style="display:none;">
        <table id="permTable" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Module</th>

                <th>
                    <label class="d-flex align-items-center gap-2">
                        View
                        <input type="checkbox" id="selectAllView">
                    </label>
                </th>

                <th>
                    <label class="d-flex align-items-center gap-2">
                        Create
                        <input type="checkbox" id="selectAllCreate">
                    </label>
                </th>

                <th>
                    <label class="d-flex align-items-center gap-2">
                        Edit
                        <input type="checkbox" id="selectAllEdit">
                    </label>
                </th>

                <th>
                    <label class="d-flex align-items-center gap-2">
                        Delete
                        <input type="checkbox" id="selectAllDelete">
                    </label>
                </th>
            </tr>
            </thead>

            <tbody></tbody>
        </table>
    </div>

    <!-- SUBMIT -->
    <button id="submitPermission" class="btn btn-primary mt-3">Submit</button>

</div>

{{-- DEFAULT DATA --}}
@php
$defaultData = [
    "role" => $users->role_id ?? null,
    "moduleList" => $users->modules ? $users->modules->map(function($m) {
        return [
            "module" => $m->module,
            "permission" => [
                "view" => (int)$m->is_view,
                "create" => (int)$m->is_create,
                "edit" => (int)$m->is_edit,
                "delete" => (int)$m->is_delete
            ]
        ];
    })->toArray() : []
];
@endphp

<script>
let defaultData = @json($defaultData);
</script>


<script>

// ---------------- TEMP STORAGE ----------------
let tempPermissions = {};
let isLoadingDefaults = false;


// ---------------- UPDATE DROPDOWN BUTTON ----------------
function updateModuleDropdownButton() {
    let selected = [...document.querySelectorAll(".moduleChk:checked")].map(x => x.value);
    document.getElementById("moduleDropdownBtn").innerText =
        selected.length ? selected.join(", ") : "Select Modules";
}


// ---------------- SAVE PERMISSIONS ----------------
function saveCurrentPermissions() {
    if (isLoadingDefaults) return;

    tempPermissions = {};

    document.querySelectorAll("#permTable tbody tr").forEach(row => {
        let module = row.dataset.page;

        tempPermissions[module] = {
            view: row.querySelector('input[value="View"]').checked ? 1 : 0,
            create: row.querySelector('input[value="Create"]').checked ? 1 : 0,
            edit: row.querySelector('input[value="Edit"]').checked ? 1 : 0,
            delete: row.querySelector('input[value="Delete"]').checked ? 1 : 0
        };
    });
}

function formatModuleName(name) {
    return name.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase());
}


// ---------------- BUILD TABLE ----------------
function updateModulesInTable() {
    saveCurrentPermissions();

    let tbody = document.querySelector("#permTable tbody");
    tbody.innerHTML = "";

    let selected = [...document.querySelectorAll(".moduleChk:checked")].map(x => x.value);

    selected.forEach(module => {
        let row = document.createElement("tr");
        row.dataset.page = module;

        row.innerHTML = `
            <td class="fw-bold text-capitalize">${formatModuleName(module)}</td>
            <td><input type="checkbox" value="View"></td>
            <td><input type="checkbox" value="Create"></td>
            <td><input type="checkbox" value="Edit"></td>
            <td><input type="checkbox" value="Delete"></td>
        `;

        tbody.appendChild(row);
    });

    restoreSavedPermissions();
    syncTableToDropdown();
}


// ---------------- RESTORE PERMISSIONS ----------------
function restoreSavedPermissions() {
    document.querySelectorAll("#permTable tbody tr").forEach(row => {
        let module = row.dataset.page;

        if (tempPermissions[module]) {
            row.querySelector('input[value="View"]').checked = tempPermissions[module].view == 1;
            row.querySelector('input[value="Create"]').checked = tempPermissions[module].create == 1;
            row.querySelector('input[value="Edit"]').checked = tempPermissions[module].edit == 1;
            row.querySelector('input[value="Delete"]').checked = tempPermissions[module].delete == 1;
        }
    });
}


// ---------------- TOGGLE TABLE ----------------
function toggleTableVisibility() {
    document.getElementById("tableSection").style.display =
        document.querySelectorAll(".moduleChk:checked").length ? "block" : "none";
}


// ---------------- SYNC TABLE → DROPDOWN ----------------
function syncTableToDropdown() {
    ['View', 'Create', 'Edit', 'Delete'].forEach(action => {
        let anyChecked = [...document.querySelectorAll(`#permTable tbody input[value="${action}"]`)]
            .some(i => i.checked);

        let dropdownChk = document.querySelector(`.actionChk[value="${action}"]`);
        if (dropdownChk) dropdownChk.checked = anyChecked;
    });
}


// ---------------- MODULE CHECKBOX LISTENERS ----------------
function setupModuleCheckboxListeners() {
    document.querySelectorAll(".moduleChk").forEach(chk => {
        chk.addEventListener("change", () => {
            updateModuleDropdownButton();
            updateModulesInTable();
            toggleTableVisibility();
        });
    });
}


// ---------------- LOAD DEFAULT DATA (EDIT MODE) ----------------
function loadDefaultData() {
    if (!defaultData) return;

    isLoadingDefaults = true;

    document.getElementById("roleSelect").value = defaultData.role;

    tempPermissions = {};
    defaultData.moduleList.forEach(item => {
        tempPermissions[item.module] = {
            view: item.permission.view,
            create: item.permission.create,
            edit: item.permission.edit,
            delete: item.permission.delete
        };
    });

    defaultData.moduleList.forEach(item => {
        let chk = document.querySelector(`.moduleChk[value="${item.module}"]`);
        if (chk) chk.checked = true;
    });

    updateModuleDropdownButton();
    updateModulesInTable();
    toggleTableVisibility();

    isLoadingDefaults = false;
}


// ---------------- SELECT COLUMN ----------------
function setupColumnSelect() {

    document.getElementById("selectAllView").addEventListener("change", e => {
        document.querySelectorAll('#permTable tbody input[value="View"]').forEach(chk => chk.checked = e.target.checked);
        syncTableToDropdown();
    });

    document.getElementById("selectAllCreate").addEventListener("change", e => {
        document.querySelectorAll('#permTable tbody input[value="Create"]').forEach(chk => chk.checked = e.target.checked);
        syncTableToDropdown();
    });

    document.getElementById("selectAllEdit").addEventListener("change", e => {
        document.querySelectorAll('#permTable tbody input[value="Edit"]').forEach(chk => chk.checked = e.target.checked);
        syncTableToDropdown();
    });

    document.getElementById("selectAllDelete").addEventListener("change", e => {
        document.querySelectorAll('#permTable tbody input[value="Delete"]').forEach(chk => chk.checked = e.target.checked);
        syncTableToDropdown();
    });
}


// ---------------- MODULE SEARCH ----------------
document.getElementById("moduleSearch").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    document.querySelectorAll("#moduleList label").forEach(li => {
        li.style.display = li.innerText.toLowerCase().includes(filter) ? "block" : "none";
    });
});


// ---------------- SUBMIT ----------------
document.getElementById("submitPermission").addEventListener("click", function(e) {
    e.preventDefault();

    let role = document.getElementById("roleSelect").value;

    if (!role || role === "Please select role") {
        Swal.fire({ icon: "warning", title: "Validation Error", text: "Please select a role." });
        return;
    }

    let rows = document.querySelectorAll("#permTable tbody tr");
    if (rows.length === 0) {
        Swal.fire({ icon: "warning", title: "Validation Error", text: "Please select at least one module." });
        return;
    }

    let valid = true;
    rows.forEach(row => {
        if (![...row.querySelectorAll("input[type=checkbox]")].some(c => c.checked)) valid = false;
    });

    if (!valid) {
        Swal.fire({ icon: "warning", title: "Validation Error", text: "Select at least one action for each module." });
        return;
    }

    let finalData = {
        role_name: role,
        moduleList: []
    };

    rows.forEach(row => {
        finalData.moduleList.push({
            module: row.dataset.page,
            permission: {
                view: row.querySelector('input[value="View"]').checked ? 1 : 0,
                create: row.querySelector('input[value="Create"]').checked ? 1 : 0,
                edit: row.querySelector('input[value="Edit"]').checked ? 1 : 0,
                delete: row.querySelector('input[value="Delete"]').checked ? 1 : 0
            }
        });
    });

    fetch("{{ route('admin.user_permission_user_update', $users->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(finalData)
    })
    .then(async res => {
        const data = await res.json().catch(() => null);

        if (res.ok) return data;

        let msg = "Request failed";
        if (data && data.message) msg = data.message;
        throw new Error(msg);
    })
    .then(data => {
        Swal.fire({ icon: "success", title: "Success", text: data.message || "Saved successfully" })
            .then(() => window.location.reload());
    })
    .catch(err => {
        Swal.fire({ icon: "error", title: "Error", text: err.message });
    });

});


// ---------------- INIT ----------------
window.addEventListener("DOMContentLoaded", () => {
    setupModuleCheckboxListeners();
    setupColumnSelect();
    loadDefaultData();
});

</script>

@endsection
