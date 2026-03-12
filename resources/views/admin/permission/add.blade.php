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

<div class="container">

    <h3 class="fw-bold mb-4">Permissions</h3>

    <div class="row g-4">

        <!-- ROLE -->
        <div class="col-lg-4">
            <label class="fw-bold mb-2">Role</label>
            <select class="form-select" aria-label="Default select example" name="role_name" id="roleSelect">
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
                    <!-- 🔍 Search Bar -->
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


    <!-- SUBMIT BUTTON -->
    <div class="text-center mt-4">
        <button class="submit-btn sbmtBtn ms-4" id="submitPermission">
            Submit
        </button>
    </div>

</div>

<script>
    // ---------- DROPDOWN ----------
    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
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
    setupDropdown("pageDropdownBtn", "pageCheckboxMenu");
    setupDropdown("actionDropdownBtn", "actionCheckboxMenu");


    // ---------- VARIABLES ----------
    const tableBody = document.querySelector("#permTable tbody");
    const permTable = document.getElementById("permTable");
    let pageChecks = document.querySelectorAll(".pageChk");
    let actionChecks = document.querySelectorAll(".actionChk");


    // ---------- SEARCH FILTER FOR MODULE ----------
    document.getElementById("moduleSearch").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase().trim();
        let labels = document.querySelectorAll("#moduleList label");

        labels.forEach(label => {
            let moduleName = label.textContent.trim().toLowerCase();
            if (moduleName.includes(filter)) {
                label.style.display = "flex";
            } else {
                label.style.display = "none";
            }
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

        // remove unmatched rows
        document.querySelectorAll("#permTable tbody tr").forEach(row => {
            if (!selectedPages.includes(row.dataset.page)) row.remove();
        });

        // add missing rows
        selectedPages.forEach(page => {
            if (!document.querySelector(`tr[data-page="${page}"]`)) {
                createPageRow(page);
            }
        });
    }

    function formatModuleName(name) {
        return name
            .replace(/_/g, ' ') // replace _ with space
            .replace(/\b\w/g, char => char.toUpperCase()); // capitalize first letter of each word
    }

    function createPageRow(page) {

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

        // sync dropdown → table
        syncDropdownToTable(row);

        // sync table → dropdown
        row.querySelectorAll(".tableAction").forEach(chk => {
            chk.addEventListener("change", syncTableToDropdown);
        });
    }


    // ---------- PAGE DROPDOWN TEXT ----------
    function updatePageDropdownButton() {
        const selected = [...pageChecks].filter(c => c.checked).map(c => formatModuleName(c.value));
        const btn = document.getElementById("pageDropdownBtn");

        if (selected.length === 0) {
            btn.innerText = "Select Module";
        } else if (selected.length <= 4) {
            btn.innerText = selected.join(", ");
        } else {
            btn.innerText = selected.length + " selected";
        }
    }

    // ---------- ACTION SELECT ----------
    actionChecks.forEach(chk => {
        chk.addEventListener("change", () => {
            document.querySelectorAll(".tableAction").forEach(tChk => {
                if (tChk.value === chk.value) {
                    tChk.checked = chk.checked;
                }
            });
            updateActionDropdownButton();
        });
    });

    function updateActionDropdownButton() {
        let selected = [...actionChecks].filter(c => c.checked).map(c => c.value);
        document.getElementById("actionDropdownBtn").innerText =
            selected.length ? selected.join(", ") : "Select Action";
    }


    // ---------- SYNC ---------
    function syncDropdownToTable(row) {
        row.querySelectorAll(".tableAction").forEach(chk => {
            let dropdownChk = [...actionChecks].find(a => a.value === chk.value);
            chk.checked = dropdownChk.checked;
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


    // ---------- SUBMIT ----------
    document.getElementById("submitPermission").addEventListener("click", function(e) {
        e.preventDefault();

        let role = document.getElementById("roleSelect").value;

        // Validation: role
        if (role === "" || role === "Please select role") {
            Swal.fire({
                icon: "warning",
                title: "Validation Error",
                text: "Please select a role."
            });
            return;
        }

        // Validation: module table
        let selectedRows = document.querySelectorAll("#permTable tbody tr");
        if (selectedRows.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "Validation Error",
                text: "Please select at least one module."
            });
            return;
        }

        let validActions = true;
        selectedRows.forEach(row => {
            let list = row.querySelector('input[value="List"]').checked;
            let create = row.querySelector('input[value="Create"]').checked;
            let view = row.querySelector('input[value="View"]').checked;
            let edit = row.querySelector('input[value="Edit"]').checked;
            let del = row.querySelector('input[value="Delete"]').checked;
            if (!list && !create && !edit && !del && !view) validActions = false;
        });

        if (!validActions) {
            Swal.fire({
                icon: "warning",
                title: "Validation Error",
                text: "Please select at least one action for each module."
            });
            return;
        }

        // Prepare final data
        let finalData = {
            role_name: role,
            moduleList: []
        };

        selectedRows.forEach(row => {
            let moduleName = row.dataset.page;
            let list = row.querySelector('input[value="List"]').checked ? 1 : 0;
            let create = row.querySelector('input[value="Create"]').checked ? 1 : 0;
            let view = row.querySelector('input[value="View"]').checked ? 1 : 0;
            let edit = row.querySelector('input[value="Edit"]').checked ? 1 : 0;
            let del = row.querySelector('input[value="Delete"]').checked ? 1 : 0;

            finalData.moduleList.push({
                module: moduleName,
                permission: {
                    list: list,
                    create: create,
                    edit: edit,
                    delete: del,
                    view: view
                }
            });
        });

        // Submit via fetch — expect JSON back
        fetch("{{ route('admin.user_permission_insert') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json", // <--- ask Laravel to send JSON responses
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(finalData)
            })
            .then(async (res) => {
                const data = await res.json().catch(() => null); // attempt parse JSON (may be null)
                if (res.ok) {
                    // 200 OK
                    return data;
                }

                // If not ok: handle status codes (validation = 422, server error = 500, etc)
                if (res.status === 422 && data) {
                    // Validation errors
                    // Laravel validation errors usually in data.errors (object)
                    const errors = data.errors || data;
                    let msg = '';
                    if (typeof errors === 'object') {
                        msg = Object.values(errors).flat().join('\n');
                    } else {
                        msg = data.message || 'Validation failed';
                    }
                    throw new Error(msg);
                } else {
                    // Other errors — show message if available
                    const errMsg = (data && (data.message || JSON.stringify(data))) || `Request failed with status ${res.status}`;
                    throw new Error(errMsg);
                }
            })
            .then(data => {
                // success (controller returned JSON)
                Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.message || 'Saved successfully'
                    })
                    .then(() => {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            // optional: reload or redirect to list
                            window.location.reload();
                        }
                    });
            })
            .catch(err => {
                // show friendly error
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: err.message || "Something went wrong!"
                });
            });
    });
</script>

@endsection