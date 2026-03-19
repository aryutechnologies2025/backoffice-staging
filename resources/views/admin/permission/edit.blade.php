@extends('layouts.app')

@section('content')

<style>
    .checkbox-dropdown {
        position: relative;
        width: 100%;
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
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        display: none;
        z-index: 10;
    }

    .checkbox-list label {
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        padding: 4px 0;
    }

    table th,
    table td {
        padding: 12px;
        text-align: center;
    }

    #permTable {
        display: none;
    }

    .checkbox-list {
        max-height: 250px;
        overflow-y: auto;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6">
        <h3 class="admin-title fw-bold">Edit Permission</h3>
    </div>
    <div class="text-end col-lg-6">
        <b>
            <a href="/dashboard">Dashboard</a> >
            <a href="{{ route('admin.user_permission_list') }}">Permission</a> >
            <a style="color:blue;">Edit</a>
        </b>
    </div>
</div>

<div class="row mb-5">
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">

            <div class="row g-4">

                <!-- ROLE -->
                <div class="col-lg-4">
                    <label class="fw-bold mb-2">Role</label>
                    <select class="form-select" name="role_name" id="roleSelect">
                        <option selected>Please select role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- MODULE MULTI SELECT with SEARCH -->
                <div class="col-lg-4">
                    <label class="fw-bold mb-2">Module</label>
                    <div class="checkbox-dropdown">
                        <button type="button" class="form-control" id="pageDropdownBtn">
                            Select Module
                        </button>
                        <div class="checkbox-list shadow-sm p-2" id="pageCheckboxMenu">
                            <input type="text" id="moduleSearch" class="form-control mb-2" placeholder="Search module...">
                            <div id="moduleList">
                                @foreach($modules as $key => $module)
                                <label class="d-block">
                                    <input type="checkbox" class="pageChk" name="modules[]" value="{{ $key }}">
                                    {{ $module }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION MULTI SELECT -->
                <div class="col-lg-4">
                    <label class="fw-bold mb-2">Action</label>
                    <div class="checkbox-dropdown">
                        <button type="button" class="form-control" id="actionDropdownBtn">
                            Select Action
                        </button>
                        <div class="checkbox-list shadow-sm p-2" id="actionCheckboxMenu">
                            <label><input type="checkbox" class="actionChk" value="List"> List</label>
                            <label><input type="checkbox" class="actionChk" value="Create"> Create</label>
                            <label><input type="checkbox" class="actionChk" value="View"> View</label>
                            <label><input type="checkbox" class="actionChk" value="Edit"> Edit</label>
                            <label><input type="checkbox" class="actionChk" value="Delete"> Delete</label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABLE -->
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="permTable">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th width="50%">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <!-- SUBMIT -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.user_permission_list') }}">
                    <button type="button" class="cancel-btn">Cancel</button>
                </a>
                <button class="submit-btn sbmtBtn ms-4" id="submitPermission">Submit</button>
            </div>

        </div>
    </div>
</div>

@php
$defaultData = [
    "role" => $users->role_id ?? null,
    "moduleList" => $users->modules ? $users->modules->map(function($m) {
        return [
            "module" => $m->module,
            "permission" => [
                "list"   => (int)$m->is_list,
                "view"   => (int)$m->is_view,
                "create" => (int)$m->is_create,
                "edit"   => (int)$m->is_edit,
                "delete" => (int)$m->is_delete,
            ]
        ];
    })->toArray() : []
];
@endphp

<script>
let defaultData = @json($defaultData);

// ---------- DROPDOWN ----------
function setupDropdown(btnId, menuId) {
    const btn  = document.getElementById(btnId);
    const menu = document.getElementById(menuId);
    btn.addEventListener("click", () => {
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });
    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = "none";
        }
    });
}
setupDropdown("pageDropdownBtn",   "pageCheckboxMenu");
setupDropdown("actionDropdownBtn", "actionCheckboxMenu");

// ---------- VARIABLES ----------
const tableBody  = document.querySelector("#permTable tbody");
const permTable  = document.getElementById("permTable");
let   pageChecks = document.querySelectorAll(".pageChk");
let   actionChecks = document.querySelectorAll(".actionChk");

// ---------- MODULE SEARCH ----------
document.getElementById("moduleSearch").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase().trim();
    document.querySelectorAll("#moduleList label").forEach(label => {
        label.style.display = label.textContent.trim().toLowerCase().includes(filter) ? "flex" : "none";
    });
});

// ---------- PAGE SELECT ----------
function refreshPageCheckboxListeners() {
    pageChecks = document.querySelectorAll(".pageChk");
    pageChecks.forEach(chk => {
        chk.addEventListener("change", () => {
            updatePagesInTable();
            updatePageDropdownButton();
            toggleTableVisibility();
        });
    });
}
refreshPageCheckboxListeners();

function toggleTableVisibility() {
    let selectedPages = [...pageChecks].filter(c => c.checked);
    permTable.style.display = selectedPages.length ? "table" : "none";
}

function updatePagesInTable() {
    let selectedPages = [...pageChecks].filter(c => c.checked).map(c => c.value);

    document.querySelectorAll("#permTable tbody tr").forEach(row => {
        if (!selectedPages.includes(row.dataset.page)) row.remove();
    });

    selectedPages.forEach(page => {
        if (!document.querySelector(`tr[data-page="${page}"]`)) {
            createPageRow(page);
        }
    });
}

function formatModuleName(name) {
    return name.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
}

function createPageRow(page, savedPerm) {
    let row = document.createElement("tr");
    row.setAttribute("data-page", page);

    row.innerHTML = `
        <td>${formatModuleName(page)}</td>
        <td>
            <label><input type="checkbox" class="tableAction" value="List"> List</label>
            &nbsp;&nbsp;
            <label><input type="checkbox" class="tableAction" value="Create"> Create</label>
            &nbsp;&nbsp;
            <label><input type="checkbox" class="tableAction" value="View"> View</label>
            &nbsp;&nbsp;
            <label><input type="checkbox" class="tableAction" value="Edit"> Edit</label>
            &nbsp;&nbsp;
            <label><input type="checkbox" class="tableAction" value="Delete"> Delete</label>
        </td>
    `;

    tableBody.appendChild(row);

    // restore saved permissions if provided
    if (savedPerm) {
        row.querySelector('input[value="List"]').checked   = savedPerm.list   == 1;
        row.querySelector('input[value="Create"]').checked = savedPerm.create == 1;
        row.querySelector('input[value="View"]').checked   = savedPerm.view   == 1;
        row.querySelector('input[value="Edit"]').checked   = savedPerm.edit   == 1;
        row.querySelector('input[value="Delete"]').checked = savedPerm.delete == 1;
    } else {
        syncDropdownToTable(row);
    }

    row.querySelectorAll(".tableAction").forEach(chk => {
        chk.addEventListener("change", syncTableToDropdown);
    });
}

// ---------- PAGE DROPDOWN TEXT ----------
function updatePageDropdownButton() {
    const selected = [...pageChecks].filter(c => c.checked).map(c => formatModuleName(c.value));
    const btn = document.getElementById("pageDropdownBtn");
    if (selected.length === 0)       btn.innerText = "Select Module";
    else if (selected.length <= 4)   btn.innerText = selected.join(", ");
    else                             btn.innerText = selected.length + " selected";
}

// ---------- ACTION SELECT ----------
actionChecks.forEach(chk => {
    chk.addEventListener("change", () => {
        document.querySelectorAll(".tableAction").forEach(tChk => {
            if (tChk.value === chk.value) tChk.checked = chk.checked;
        });
        updateActionDropdownButton();
    });
});

function updateActionDropdownButton() {
    let selected = [...actionChecks].filter(c => c.checked).map(c => c.value);
    document.getElementById("actionDropdownBtn").innerText =
        selected.length ? selected.join(", ") : "Select Action";
}

// ---------- SYNC ----------
function syncDropdownToTable(row) {
    row.querySelectorAll(".tableAction").forEach(chk => {
        let dropdownChk = [...actionChecks].find(a => a.value === chk.value);
        if (dropdownChk) chk.checked = dropdownChk.checked;
    });
}

function syncTableToDropdown() {
    let allTableChecks = document.querySelectorAll(".tableAction");
    actionChecks.forEach(aChk => {
        let matching = [...allTableChecks].filter(t => t.value === aChk.value);
        aChk.checked = matching.some(m => m.checked);
    });
    updateActionDropdownButton();
}

// ---------- LOAD DEFAULT DATA ----------
function loadDefaultData() {
    if (!defaultData || !defaultData.moduleList) return;

    document.getElementById("roleSelect").value = defaultData.role;

    // build a map for quick lookup
    let permMap = {};
    defaultData.moduleList.forEach(item => {
        permMap[item.module] = item.permission;
    });

    // check the module checkboxes
    defaultData.moduleList.forEach(item => {
        let chk = document.querySelector(`.pageChk[value="${item.module}"]`);
        if (chk) chk.checked = true;
    });

    updatePageDropdownButton();

    // create rows with saved permissions
    defaultData.moduleList.forEach(item => {
        if (!document.querySelector(`tr[data-page="${item.module}"]`)) {
            createPageRow(item.module, item.permission);
        }
    });

    toggleTableVisibility();
}

// ---------- SUBMIT ----------
document.getElementById("submitPermission").addEventListener("click", function(e) {
    e.preventDefault();

    let role = document.getElementById("roleSelect").value;

    if (!role || role === "Please select role") {
        Swal.fire({ icon: "warning", title: "Validation Error", text: "Please select a role." });
        return;
    }

    let selectedRows = document.querySelectorAll("#permTable tbody tr");
    if (selectedRows.length === 0) {
        Swal.fire({ icon: "warning", title: "Validation Error", text: "Please select at least one module." });
        return;
    }

    let validActions = true;
    selectedRows.forEach(row => {
        let anyChecked = [...row.querySelectorAll(".tableAction")].some(c => c.checked);
        if (!anyChecked) validActions = false;
    });

    if (!validActions) {
        Swal.fire({ icon: "warning", title: "Validation Error", text: "Please select at least one action for each module." });
        return;
    }

    let finalData = { role_name: role, moduleList: [] };

    selectedRows.forEach(row => {
        finalData.moduleList.push({
            module: row.dataset.page,
            permission: {
                list:   row.querySelector('input[value="List"]').checked   ? 1 : 0,
                view:   row.querySelector('input[value="View"]').checked   ? 1 : 0,
                create: row.querySelector('input[value="Create"]').checked ? 1 : 0,
                edit:   row.querySelector('input[value="Edit"]').checked   ? 1 : 0,
                delete: row.querySelector('input[value="Delete"]').checked ? 1 : 0,
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
        throw new Error((data && data.message) || "Request failed");
    })
    .then(data => {
        Swal.fire({ icon: "success", title: "Success", text: data.message || "Saved successfully" })
            .then(() => {
                window.location.href = data.redirect_url || "{{ route('admin.user_permission_list') }}";
            });
    })
    .catch(err => {
        Swal.fire({ icon: "error", title: "Error", text: err.message || "Something went wrong!" });
    });
});

// ---------- INIT ----------
window.addEventListener("DOMContentLoaded", loadDefaultData);
</script>

@endsection
