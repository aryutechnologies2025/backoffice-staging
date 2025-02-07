@extends('layouts.app')
@section('content')

<style>
    .modal {
        width: 100% !important;
        justify-content: center;
    }

    a:hover {
        color: red;
    }

    a {
        color: rgb(37, 150, 190);
    }

    .city {
        color: blue;
    }
</style>

<div class="container-wrapper pt-5">
    <div class="row">
        <div class="col-md-12 mt-5 mb-6">
            <b><a href="/dashboard">Dashboard</a> > <a class="city" href="">Program PDF</a></b>
            <br>
            <br>
            <h3 class="mb-3">Program PDF</h3>
            <form method="POST" action="{{ route('admin.program_pdf_insert') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-2 mb-4">
                    <div class="col-md-6 mb-2">
                        <label class="fw-bold mb-2"> Program Name <span class="text-danger">*</span></label>
                        <select id="program_name" name="program_name" class="form-control py-2 rounded-3 shadow-sm" required>
                            <option value="">Select Program</option>
                            @foreach($program_dts as $id => $name)
                            <option value="{{ $id }}" @if(old('program_name')==$id) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="pdf" class="fw-bold mb-2">PDF</label>
                        <input type="file" name="program_pdf" id="program_pdf" class="form-control py-2 rounded-3 shadow-sm">
                    </div>
                </div>
                <button type="submit" class="btn btn-success text-white">Add Program PDF</button>
            </form>
            <hr>
            <table class="table table-striped" id="programPdfTable">
                <thead>
                    <tr>
                        <th>Program</th>
                        <th>PDF</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pdfs as $program_pdf)
                    <tr>
                        <td>{{ $program_pdf->program_name }}</td>
                        <td>{{ $program_pdf->program_pdf }}</td>
                        <td>
                            <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $program_pdf->id }}">
                                <i class="bi bi-pencil-square"></i></a>

                            <!-- Edit Modal -->
                            <div class="modal fade py-5" id="editModal{{ $program_pdf->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $program_pdf->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $program_pdf->id }}">Edit Program PDF</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('admin.program_pdf_updates', $program_pdf->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="row g-2 mb-4">
                                                    <div class="col-md-6">
                                                        <label class="fw-bold mb-2"> Program Name <span class="text-danger">*</span></label>
                                                        <select id="program_name" name="program_name" class="form-control py-2 rounded-3 shadow-sm" required>
                                                            <option value="">Select Program</option>
                                                            @foreach($program_dts as $id => $name)
                                                            <option value="{{ $id }}" @if($program_pdf->program_name == $id) selected @endif>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label for="pdf" class="fw-bold mb-2">PDF</label>
                                                    <input type="file" name="program_pdf" id="program_pdf" class="form-control">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update Program PDF</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="delete-form-{{ $program_pdf->id }}" method="POST" action="{{ route('admin.program_pdf_delete', $program_pdf->id) }}" class="delete-form">
                                @csrf
                                @method('POST')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $program_pdf->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#programPdfTable').DataTable();

        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this item?')) {
                    form.submit();
                }
            });
        });
    });




    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection