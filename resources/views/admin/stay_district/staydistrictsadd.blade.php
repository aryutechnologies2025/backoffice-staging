@extends('layouts.app')
@Section('content')

<div class="row body-sec py-3 px-5 justify-content-around">
    <div class="text-start col-lg-6 ">
        <h3 class="admin-title fw-bold">{{$title}}</h3>
    </div>
    <div class="text-end col-lg-6 ">
        <b><a href="/dashboard">Dashboard</a> > <a class="city" href="/staydestrict">Stay Districts</a></b>
    </div>

</div>



<div class="row mb-5">
    <div class="col-lg-12">
        <!-- INFORMATION -->
        <div class="form-body px-4 mb-5 ms-4 me-5 rounded-4">
            <!-- FORM -->
            <form method="POST" action="{{ route('admin.staydistricts_insert') }}" enctype="multipart/form-data" id="districts-form">
                @csrf
                <h3 class="add_head fw-bold">{{ $title }}</h3>
                <div id="districts-container">
                    <!-- Initial district field set -->
                    <div class="add_form form-group py-4">

                        <label class="mb-2">Destination <span class="text-danger">*</span></label>
                        <select id="cities_name" name="cities_name" class="form-select py-2 rounded-3 shadow-sm" required>
                            <option value="" disabled selected>Select Destination</option>
                            @foreach ($destination_dts as $id => $name)
                            <option value="{{ $id }}" @if (old('cities_name')=='{{ $id }}' ) selected @endif>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="district-fieldset mb-4 p-3 border rounded">


                        <div class="add_form form-group">
                            <label>District Name</label>
                            <input type="text" class="form-control mt-1" name="districts[0][destination]" required>
                        </div>

                        <div class="add_form form-group mt-3">
                            <label>District Image</label>
                            <input type="file" class="form-control-file mt-1" name="districts[0][image]">
                        </div>

                        <div class="add_form form-group mt-3">
                            <label>Description</label>
                            <textarea class="form-control mt-1" name="districts[0][description]" rows="3"></textarea>
                        </div>



                        <button type="button" class="btn btn-danger text-white btn-sm remove-district mt-2" style="display: none;">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>

                <button type="button" id="add-district" class="btn  text-white btn-secondary m-3">
                    Add Another District
                </button>

                <button type="submit" class="btn btn-primary text-white m-3">
                    Save All Districts
                </button>
                <button type="submit" class="btn btn-primary text-white m-3">
                    <a href="{{ route('admin.staydistrictlist') }}" class="text-white text-decoration-none">
                        Back to District List
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let districtCount = 1;

        // Add new district fields
        $('#add-district').click(function() {
            const newFieldset = $('.district-fieldset:first').clone();

            // Clear all values
            newFieldset.find('input[type="text"]').val('');
            newFieldset.find('textarea').val('');
            newFieldset.find('input[type="file"]').val('');


            // Update names with new index
            newFieldset.find('[name]').each(function() {
                const name = $(this).attr('name').replace(/\[\d+\]/, '[' + districtCount + ']');
                $(this).attr('name', name);
            });

            // Show remove button
            newFieldset.find('.remove-district').show();

            // Add remove functionality
            newFieldset.find('.remove-district').click(function() {
                $(this).closest('.district-fieldset').remove();
            });

            $('#districts-container').append(newFieldset);
            districtCount++;
        });

        // Remove functionality for initial fieldset
        $('.remove-district').click(function() {
            $(this).closest('.district-fieldset').remove();
        });
    });
</script>
@endsection