@extends('layouts.app')

@section('content')
<style>
    a:hover {
        color: rgb(27, 108, 138);
    }

    a {
        color: rgb(37, 150, 190);
    }

    .city {
        color: rgb(27, 108, 138);
    }

    .district-fieldset {
        position: relative;
        padding: 20px;
        margin-bottom: 20px;
        background: #f8f9fa;
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .remove-district {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">Edit Stay Districts</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a href="{{ route('admin.staydistrictlist') }}">Stay District</a> > <span class="city">Edit</span></b>
    </div>

</div>

<div class="row mb-5">  
    <div class="col-lg-12">
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <form method="POST" action="{{ route('admin.staydistrict_update', $destination->id) }}" enctype="multipart/form-data" id="districts-form">
                @csrf

                <div id="districts-container" data-initial-count="{{ count($destination->districts_data) }}">
                    <div class="add_form form-group">

                        <label class="mb-2">Destination <span class="text-danger">*</span></label>
                        <select id="cities_name" name="cities_name"
                            class="form-select py-2 rounded-3 shadow-sm" required>
                            <option value="" disabled selected>Select Destination</option>
                            @foreach($destination_dts as $id => $name)
                            <option value="{{ $id }}" @if($destination->destination == $id) selected @endif>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @foreach($destination->districts_data as $index => $district)
                    <div class="district-fieldset mb-4 mt-3 p-3 border rounded">
                        <div class="add_form form-group mb-3">
                            <label class="form-label">District Name</label>
                            <input type="text" class="form-control" name="districts[{{ $index }}][destination]" value="{{ $district['destination'] }}" required>
                        </div>

                        <div class="add_form form-group mb-3">
                            <label class="form-label">District Image</label>

                            <!-- File input for new image -->
                            <input type="file" class="form-control-file" name="districts[{{ $index }}][image]">

                            @if(isset($district['image_path']) && $district['image_path'])
                            <!-- Current image display with remove option -->
                            <div class="mt-2">
                                <img src="{{ asset($district['image_path']) }}" width="100" class="img-thumbnail mb-2">



                                <!-- Hidden field to preserve existing image path unless changed -->
                                <input type="hidden"
                                    name="districts[{{ $index }}][existing_image]"
                                    value="{{ $district['image_path'] }}">
                            </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="districts[{{ $index }}][description]" rows="3">{{ $district['description'] ?? '' }}</textarea>
                        </div>



                        @if(count($destination->districts_data))
                        <button type="button" class="text-danger btn remove-district ">
                            <i class="fa fa-trash" style="color: red !important;"></i>
                        </button>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between my-4">
                    <button type="button" id="add-district" class="btn btn-secondary text-white">
                        <i class="fas fa-plus"></i> Add Another District
                    </button>

                    <div>
                        <a href="{{ route('admin.staydistrictlist') }}" class="cancel-btn">Cancel</a>
                        <button type="submit" class="submit-btn sbmtBtn ms-4">
                            <i class="fas fa-save"></i> Update Destination
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize districtCount with the number of existing districts
        let districtCount = parseInt($('#districts-container').data('initial-count'));


        // Add new district fields
        $('#add-district').click(function() {
            // Clone the first fieldset
            const newFieldset = $('.district-fieldset:first').clone();

            // Clear all values
            newFieldset.find('input[type="text"]').val('');
            newFieldset.find('textarea').val('');
            newFieldset.find('input[type="file"]').val('');
            newFieldset.find('input[type="checkbox"]').prop('checked', false);
            newFieldset.find('img').remove();
            newFieldset.find('input[type="hidden"]').remove();

            // Update names with new index
            newFieldset.find('[name]').each(function() {
                const name = $(this).attr('name').replace(/\[\d+\]/, '[' + districtCount + ']');
                $(this).attr('name', name);
            });

            // Update IDs to prevent duplicates
            newFieldset.find('[id]').each(function() {
                const id = $(this).attr('id').replace(/\d+/, districtCount);
                $(this).attr('id', id);
            });

            // Update labels to match new IDs
            newFieldset.find('label').each(function() {
                const forAttr = $(this).attr('for');
                if (forAttr) {
                    $(this).attr('for', forAttr.replace(/\d+/, districtCount));
                }
            });

            // Show remove button for cloned fieldsets
            newFieldset.find('.remove-district').show();

            // Add remove functionality
            newFieldset.find('.remove-district').off('click').on('click', function() {
                $(this).closest('.district-fieldset').remove();
                // Hide remove buttons if only one remains
                if ($('.district-fieldset').length === 1) {
                    $('.remove-district').hide();
                }
            });

            // Append to container
            $('#districts-container').append(newFieldset);
            districtCount++;

            // Show all remove buttons since we now have multiple
            $('.remove-district').show();
        });

        // Initial remove functionality
        $('.remove-district').on('click', function() {
            if ($('.district-fieldset').length > 1) {
                $(this).closest('.district-fieldset').remove();
                // Hide remove buttons if only one remains
                if ($('.district-fieldset').length === 1) {
                    $('.remove-district').hide();
                }
            }
        });

        // Hide remove buttons initially if only one district
        if ($('.district-fieldset').length === 1) {
            $('.remove-district').hide();
        }
    });
</script>
@endsection