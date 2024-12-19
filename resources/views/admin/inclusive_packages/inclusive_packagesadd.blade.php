@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <!-- FORM -->
    <form id="form_valid" action="{{ route('admin.inclusive_package_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <!-- 1.INFORMATION -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">1.Information</h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold  mb-4"> Theme <span class="text-danger">*</span></label>
                                <select id="themes_name" name="themes_name" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Theme</option>
                                    @foreach($themes as $id => $name)
                                    <option value="{{ $id }}" @if(old('themes_name')=='{{ $id }}' ) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4"> Destination <span class="text-danger">*</span></label>
                                <select id="cities_name" name="cities_name" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Destination</option>
                                    @foreach($cities as $id => $name)
                                    <option value="{{ $id }}" @if(old('cities_name')=='{{ $id }}' ) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-5">
                                <div class="col">
                                    <label class="fw-bold mb-4 "> Title <span class="text-danger">*</span></label>
                                    <input type="text" placeholder="Title" id="title" name="title" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('title')}}">
                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <input type="hidden" id="program_description" name="program_description">
                                        <label class="form-label form-label-top form-label-auto fw-bold mb-4">Program Description <span class="text-danger">*</span></label>
                                        <!-- <textarea id="program_description" class="container__textarea p-5 textarea-feild" name="program_description" required></textarea> -->
                                        <!-- <div class="mb-3">
                                            <div id="commentEditor1" class="form-control " style="height: 200px;"></div>
                                        </div> -->
                                        <div class=" mt-5">
                                            <div class="row">
                                                <div class="col-lg-12 ">
                                                    <div id="summernote1" style="height: 200px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mb-5">
                        <div class="col">
                            <div class="row mt-5">
                                <label class="fw-bold mb-4 "> Flag </label>
                                <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="popular_program" name="prop_cat[]" value="popular_program">
                                        <label class="form-check-label" for="popular_program">Popular Program</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="upcoming_program" name="prop_cat[]" value="upcoming_program">
                                        <label class="form-check-label" for="upcoming_program">Upcoming Program</label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="featured" name="prop_cat[]" value="featured">
                                        <label class="form-check-label" for="featured">Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="g-2">
                        <div class="col">
                            <label class="fw-bold mt-4"> Cover image </label>
                            <div class="row d-flex mb-4">
                                <div class="col-lg-2">
                                    <div class="form-input">
                                        <label for="file-ip-100" class="px-4 py-3 text-center">
                                            <img class="text-center mt-3" id="file-ip-100-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                            <p class="text-center fw-light mt-3">Add Pic</p>
                                        </label>
                                        <input type="file" id="file-ip-100" name="cover_img" accept="image/png, image/jpeg, image/svg+xml">
                                        <div id="file-ip-100-error" class="text-danger"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="fw-bold mt-4"> Gallery Image </label>
                            <div class="container">
                                <div id="photo-upload-container" class="row">
                                    <div class="col-lg-2 photo-upload-field">
                                        <div class="form-input">
                                            <label for="file-ip-1" class="px-4 py-3 text-center">
                                                <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                                <p class="text-center fw-light mt-3"> Add Pic</p>
                                            </label>
                                            <input type="file" name="img_1" id="file-ip-1" data-number="1" accept="image/png, image/jpeg, image/svg+xml">
                                        </div>
                                    </div>
                                </div>
                                <button id="add-photo-btn" type="button" class="btn btn-primary mt-3">Add More Photos</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 2.LOCATION -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">2.Location</h4>
                    <div class="mb-3">
                        @foreach($address->chunk(4) as $chunk)
                        <!-- For each chunk, create a row -->
                        <div class="row mb-3">
                            @foreach($chunk as $address)
                            <!-- Display each amenity as a column -->
                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="address-{{ $address->id }}" name="address_services[]" value="{{ $address->id }}">
                                    <label class="form-check-label" for="address-{{ $address->id }}">{{ $address->title }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <!-- 3.TOUR PLANNING  -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">3. Tour Planning <span class="text-danger">*</span></h4>
                    <div id="plan-container">
                        <!-- Initial Plan Item -->
                        <div class="row g-2 mt-5 d-flex justify-content-around">
                            <div class="col-lg-12">
                                <input type="text" name="plan_title[]" id="plan_title" class="form-control py-3 rounded-3 shadow-sm" placeholder="Plan Title" required>
                            </div>
                        </div>
                        <div class="plan-item mb-3">
                            <div class="mt-5">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="form-label form-label-top form-label-auto fw-bold mb-4">Plan Subtitle</label>
                                        <input type="text" name="plan_subtitle[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Plan Subtitle" required>
                                    </div>
                                    <div class="col-lg-1 mt-3 text-end">
                                        <a href="#" class="table-link danger remove-plan">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <input type="hidden" id="plan_description" name="plan_description[]">
                                        <label class="form-label form-label-top form-label-auto fw-bold mb-4">Plan Description <span class="text-danger">*</span></label>
                                        <!-- <textarea id="plan_description" class="container__textarea p-5 textarea-feild" name="plan_description[]" required></textarea> -->
                                        <!-- <div class="mb-3">
                                            <div id="commentEditor3" class="form-control" style="height: 200px;"></div>
                                        </div> -->
                                        <div class=" mt-5">
                                            <div class="row">
                                                <div class="col-lg-12 ">
                                                    <div id="summernote3" style="height: 200px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Added Subtitle Field -->
                        </div>
                    </div>
                    <div class="text-end p-5">
                        <button type="button" id="add-plan-btn" class="btn-add rounded border-0 px-5 py-3 text-end text-white">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>




        <!-- 4.TOUR DATE & TIME -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">4.Tour date & Time</h4>
                    <div class="mb-3">
                        <div class="row mb-4">
                            <div class="row g-2 mb-4">
                                <div class="col">
                                    <label class="fw-bold mb-4 ">Start date <span class="text-danger"></span></label>
                                    <input type="date" class="form-control py-3 rounded-3 shadow-sm " name="start_date" id="start_date" value="{{old('start_date')}}" required>
                                </div>
                                <div class="col">
                                    <label class="fw-bold mb-4 ">Return Date <span class="text-danger"></span></label>
                                    <input type="date" class="form-control py-3 rounded-3 shadow-sm " name="return_date" value="{{old('return_date')}}" id="return_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col">
                                <label class="fw-bold  mb-4">Total No.of Days</label>
                                <input type="text" class="form-control py-3 rounded-3 shadow-sm " id="total_days" name="total_days" value="{{old('total_days')}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- 5.PRICING -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">5. Pricing</h4>
                    <div class="mb-3">
                        <div class="row mb-4">
                            <div class="col-lg-6 mt-4">
                                <label class="fw-bold ">Member Capacity <span class="text-danger">*</span></label>
                                <input type="text" id="member_capacity" name="member_capacity" class="form-control py-3 rounded-3 shadow-sm" placeholder="Member Capacity" required value="{{old('member_capacity')}}">
                            </div>
                            <div class="col-lg-6 mt-4">
                                <label class="fw-bold "></label>
                                <select id="mem_type" name="mem_type" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select</option>
                                    <option value="perhead" @if(old('mem_type')=='perhead' ) selected @endif>Perhead</option>
                                    <option value="full" @if(old('mem_type')=='full' ) selected @endif>Full</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label class="fw-bold mb-4 "> Actual Amount <span class="text-danger">*</span></label>
                                <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Actual Amount" value="{{old('price')}}" required>
                            </div>
                            <div class="col-lg-6 mt-2">
                                <label class="fw-bold mb-4 "> Discount Amount <span class="text-danger">*</span></label>
                                <input type="text" id="actual_price" name="actual_price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Actual Price" value="{{old('actual_price')}}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6.rule & Regulation -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">6. Payment Policy</h4>
                    <div class="mb-3">
                        <div id="camp-rule-container">
                            <div class="row g-2 mb-4 camp-rule-field">
                                <div class="col">
                                    <label class="fw-bold mb-4">Payment Policy <span class="text-danger">*</span></label>
                                    <input type="text" name="camp_rule[]" id="camp_rule" class="form-control py-3 rounded-3 shadow-sm" placeholder="Payment Policy" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn-add rounded border-0 px-5 py-3 text-white" onclick="addCampRuleField()">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7.Important info -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">7.Important info <span class="text-danger">*</span></h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <input type="hidden" id="important_info" name="important_info">
                                <!-- <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info" value="{{old('important_info')}}" required></textarea> -->
                                <!-- <div class="mb-3">
                                    <div id="commentEditor4" class="form-control" style="height: 200px;"></div>
                                </div> -->
                                <div class=" mt-5">
                                    <div class="row">
                                        <div class="col-lg-12 ">
                                            <div id="summernote4" style="height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">8.Program Inclusion </h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <input type="hidden" id="program_inclusion" name="program_inclusion">
                                <!-- <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info" value="{{old('important_info')}}" required></textarea> -->
                                <!-- <div class="mb-3">
                                    <div id="commentEditor5" class="form-control" style="height: 200px;"></div>
                                </div> -->
                                <div class=" mt-5">
                                    <div class="row">
                                        <div class="col-lg-12 ">
                                            <div id="summernote5" style="height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">9. Location</h4>
                    <div class="mb-3">
                        <div id="camp-rule-container">
                            <div class="row g-2 mb-4 camp-rule-field">
                                <div class="col">
                                    <label class="fw-bold mb-4">Google Map<span class="text-danger">*</span></label>
                                    <input type="text" name="google_map" id="google_map" class="form-control py-3 rounded-3 shadow-sm" placeholder="Google Map" required value="{{old('google_map')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">10.Food Menu </h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="form-label form-label-top form-label-auto fw-bold mb-4">Breakfast</label>
                                <input type="hidden" id="break_fast" name="break_fast">
                                <!-- <div class="mb-3">
                                    <div id="commentEditor6" class="form-control" style="height: 200px;"></div>
                                </div> -->
                                <div class=" mt-5">
                                    <div class="row">
                                        <div class="col-lg-12 ">
                                            <div id="summernote6" style="height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="form-label form-label-top form-label-auto fw-bold mb-4">Lunch</label>
                                <input type="hidden" id="lunch" name="lunch">
                                <!-- <div class="mb-3">
                                    <div id="commentEditor7" class="form-control" style="height: 200px;"></div>
                                </div> -->
                                <div class=" mt-5">
                                    <div class="row">
                                        <div class="col-lg-12 ">
                                            <div id="summernote7" style="height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="form-label form-label-top form-label-auto fw-bold mb-4">Dinner</label>
                                <input type="hidden" id="dinner" name="dinner">
                                <!-- <div class="mb-3">
                                    <div id="commentEditor8" class="form-control" style="height: 200px;"></div>
                                </div> -->
                                <div class=" mt-5">
                                    <div class="row">
                                        <div class="col-lg-12 ">
                                            <div id="summernote8" style="height: 200px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8.AMENITIES -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">11. Amenities</h4>
                    <div class="row mb-4">
                        @foreach($amenities->chunk(4) as $chunk)
                        <!-- For each chunk, create a row -->
                        <div class="row mb-3">
                            @foreach($chunk as $amenity)
                            <!-- Display each amenity as a column -->
                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="amenity-{{ $amenity->id }}" name="amenity_services[]" value="{{ $amenity->id }}">
                                    <label class="form-check-label" for="amenity-{{ $amenity->id }}">{{ $amenity->amenity_name }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 9.FOOD & BEVERAGES -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">12. Food and Beverages</h4>
                    <div class="row mb-4">
                        @foreach($foodBeverages->chunk(6) as $chunk)
                        <div class="row mb-3">
                            @foreach($chunk as $item)
                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="food-beverage-{{ $item->id }}" name="food_beverages[]" value="{{ $item->id }}">
                                    <label class="form-check-label" for="food-beverage-{{ $item->id }}">{{ $item->food_beverage }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!--10. ACTIVITIES -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">13.Activities</h4>
                    <div class="row mb-4">
                        @foreach($activities->chunk(6) as $chunk)
                        <div class="row mb-3">
                            @foreach($chunk as $item)
                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="activities-{{ $item->id }}" name="activities[]" value="{{ $item->id }}">
                                    <label class="form-check-label" for="activities-{{ $item->id }}">{{ $item->activities }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- 11.SAFETY FEATURES  -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">14.Safety Features</h4>
                    <div class="row mb-4">
                        @foreach($safety_features->chunk(6) as $chunk)
                        <div class="row mb-3">
                            @foreach($chunk as $item)
                            <div class="col-lg-3 col-md-3 col-sm-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="safety_features-{{ $item->id }}" name="safety_features[]" value="{{ $item->id }}">
                                    <label class="form-check-label" for="safety_features-{{ $item->id }}">{{ $item->safety_features }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="fw-bold ">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" id="status" name="status">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.inclusive_package_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                    </div>
                </div>
            </div>

        </div>

    </form>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote1,#summernote2,#summernote3,#summernote4,#summernote5,#summernote6,#summernote7,#summernote8').summernote({
            height: 200 // Set the height of the editor
        });
        $('#summernote1').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('#summernote2').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote3').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote4').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote5').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote6').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote7').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        $('#summernote8').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });










        let photoCount = 1; // Start with existing photo field count

        // Function to generate new photo upload field HTML
        function createPhotoUploadField(count) {
            return `
                <div class="col-lg-2 photo-upload-field">
                    <div class="form-input">
                        <label for="file-ip-${count}" class="px-4 py-3 text-center">
                            <img class="text-center mt-3" id="file-ip-${count}-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                            <p class="text-center fw-light mt-3"> Add Pic</p>
                        </label>
                        <input type="file" name="img_${count}" id="file-ip-${count}" data-number="${count}" accept="image/*">
                    </div>
                </div>
            `;
        }

        // Event listener for the "Add More Photos" button
        $('#add-photo-btn').on('click', function() {
            photoCount++;
            const newFieldHtml = createPhotoUploadField(photoCount);
            $('#photo-upload-container').append(newFieldHtml);
        });

        // Function to show preview of selected image
        function showPreview(event, number) {
            var file = event.target.files[0];
            var reader = new FileReader();
            var previewId = "#file-ip-" + number + "-preview";
            var errorMessageId = "#file-ip-" + number + "-error";

            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
                $(errorMessageId).text(''); // Clear any previous error message
            };

            if (file) {
                if (file.size <= 2 * 1024 * 1024) { // 2 MB limit
                    if (file.type === 'image/png' || file.type === 'image/jpeg') {
                        reader.readAsDataURL(file);
                    } else {
                        $(errorMessageId).text('Please upload a valid PNG or JPEG image.');
                    }
                } else {
                    $(errorMessageId).text('File size exceeds 2 MB limit.');
                }
            }
        }

        // Delegate event binding for dynamically added file inputs
        $('#photo-upload-container').on('change', 'input[type="file"]', function(event) {
            var number = $(this).data('number'); // Use data attribute to get the number
            showPreview(event, number);
        });
    });


    // document.addEventListener('DOMContentLoaded', function() {
    //     let planCount = 1; // Initialize with existing plan count

    //     // Function to clone the existing plan item
    //     function createPlanFields() {
    //         planCount++; // Increment plan count
    //         const container = document.getElementById('plan-container');
    //         const template = container.querySelector('.plan-item');

    //         // Clone the template
    //         const newPlan = template.cloneNode(true);

    //         // Update IDs and Names for new plan items
    //         newPlan.querySelectorAll('input, textarea').forEach((field) => {
    //             // Ensure name is array-style and IDs are unique
    //             field.name = field.name.replace(/\[\]$/, `[]`);
    //             field.id = field.id + '-' + planCount;
    //         });

    //         container.appendChild(newPlan);
    //     }

    //     // Event listener for the "Add" button
    //     document.getElementById('add-plan-btn').addEventListener('click', function() {
    //         createPlanFields();
    //     });

    //     // Event delegation to handle removal of plan items
    //     document.getElementById('plan-container').addEventListener('click', function(event) {
    //         if (event.target.closest('.remove-plan')) {
    //             event.preventDefault();
    //             const planItem = event.target.closest('.plan-item');
    //             planItem.remove();
    //         }
    //     });
    // });

    document.addEventListener('DOMContentLoaded', function() {
        let planCount = 1; // Initialize with existing plan count

        // Function to clone the existing plan item
        function createPlanFields() {
            planCount++; // Increment plan count
            const container = document.getElementById('plan-container');
            const template = container.querySelector('.plan-item');

            // Clone the template
            const newPlan = template.cloneNode(true);

            // Update IDs and Names for new plan items
            newPlan.querySelectorAll('input, textarea').forEach((field) => {
                // Ensure name is array-style and IDs are unique
                field.name = field.name.replace(/\[\]$/, `[]`);
                field.id = field.id + '-' + planCount;
            });

            container.appendChild(newPlan);

            // Re-initialize Summernote for newly added fields
            $('#summernote3').summernote({
                height: 200
            });
        }

        // Event listener for the "Add" button
        document.getElementById('add-plan-btn').addEventListener('click', function() {
            createPlanFields();
        });

        // Event delegation to handle removal of plan items
        document.getElementById('plan-container').addEventListener('click', function(event) {
            if (event.target.closest('.remove-plan')) {
                event.preventDefault();
                const planItem = event.target.closest('.plan-item');
                planItem.remove();
            }
        });
    });




    function addCampRuleField() {
        // Find the container where new fields will be added
        var container = document.getElementById('camp-rule-container');

        // Create a new div for the new field
        var newField = document.createElement('div');
        newField.className = 'row g-2 mb-4 camp-rule-field';
        newField.innerHTML = `<div class="col">
                <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Payment Policy" required>
            </div>
            <div class="col-lg-1 mt-5 text-end">
                <a class="table-link danger remove-plan" onclick="removeField(this)">
                    <span class="fa-stack">
                        <i class="fa fa-square fa-stack-2x"></i>
                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                    </span>
                </a>
            </div>`;

        // Append the new field to the container
        container.appendChild(newField);
    }

    function removeField(element) {
        // Find the parent element (field container) and remove it
        var field = element.closest('.camp-rule-field');
        if (field) {
            field.remove();
        }
    }


    // total duration
    document.addEventListener('DOMContentLoaded', () => {
        const startDateInput = document.getElementById('start_date');
        const returnDateInput = document.getElementById('return_date');
        const totalDaysInput = document.getElementById('total_days');

        function calculateDays() {
            const startDate = new Date(startDateInput.value);
            const returnDate = new Date(returnDateInput.value);
            if (startDate && returnDate && returnDate >= startDate) {
                const diffTime = Math.abs(returnDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                totalDaysInput.value = diffDays;
            } else {
                totalDaysInput.value = '';
            }
        }

        startDateInput.addEventListener('change', calculateDays);
        returnDateInput.addEventListener('change', calculateDays);
    });
</script>
@endsection