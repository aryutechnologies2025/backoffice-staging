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
                                <label class="fw-bold mb-4 "> Title <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Title" id="title" name="title" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('title')}}">
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4"> Location <span class="text-danger">*</span></label>
                                <select id="cities_name" name="cities_name" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Destination</option>
                                    @foreach($cities as $id => $name)
                                    <option value="{{ $id }}" @if(old('cities_name')=='{{ $id }}' ) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-5">
                    <div class="col-lg-6">
                                <label class="fw-bold mb-4 ">Price <span class="text-danger">*</span></label>
                                <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Per day" value="{{old('price')}}" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 ">Price <span class="text-danger">*</span></label>
                                <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Per day" value="{{old('price')}}" required>
                            </div>
                    </div>
                    <div class="row g-2 mb-4">
                        <div class="col">
                            <label class="fw-bold  mb-4"> Rating <span class="text-danger">*</span></label>
                            <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Per day" value="{{old('price')}}" required>
                        </div>
                        <div id="type-container" class="col">
                        <label class="fw-bold  mb-4"> No of review <span class="text-danger">*</span></label>
                        <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Per day" value="{{old('price')}}" required>
                        </div>
                    </div>
                   
                    <div class="row g-2">
                        <div class="col">
                            <div class="container">
                                <div id="photo-upload-container" class="row">
                                    <div class="col-lg-2 photo-upload-field">
                                        <div class="form-input">
                                            <label for="file-ip-1" class="px-4 py-3 text-center">
                                                <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpece_addpic_icon.svg">
                                                <p class="text-center fw-light mt-3"> Add Pic</p>
                                            </label>
                                            <input type="file" name="img_1" id="file-ip-1" data-number="1" accept="image/*">
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


        <!-- 8.AMENITIES -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">8. Amenities</h4>
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
                    <h4 class="fw-bold mb-5">9. Food and Beverages</h4>
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
                    <h4 class="fw-bold mb-5">10.Activities</h4>
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
                    <h4 class="fw-bold mb-5">11.Safety Features</h4>
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
                            <label class="fw-bold ">IS Featured</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input check_bx" type="checkbox" name="is_featured" id="is_featured">
                            </div>
                        </div>
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

@endsection