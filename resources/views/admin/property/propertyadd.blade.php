@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <!-- FORM -->
    <form id="form_valid" action="{{ route('admin.property_insert') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <!-- 1.INFORMATION -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">1.Information</h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 ">Property tittle</label>
                                <input type="text" placeholder="Property Title" id="property_title" name="property_title" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('property_title')}}">
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4">Property Type</label>
                                <select id="property_type" name="property_type" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Type</option>
                                    <option value="villa" @if(old('property_type')=='villa' ) selected @endif>Villa</option>
                                    <option value="appartment" @if(old('property_type')=='appartment' ) selected @endif>Appartment</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4">Property Category</label>
                                <select id="prop_cat" name="prop_cat" class="form-select py-3 rounded-3 shadow-sm" onchange="updateTypeOptions()" required>
                                    <option value="">Select Category</option>
                                    <option value="events" @if(old('prop_cat')=='events' ) selected @endif>Events</option>
                                    <option value="packages" @if(old('prop_cat')=='packages' ) selected @endif>Packages</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-5">
                        <div id="type-container" class="col" style="display: none;">
                            <label class="fw-bold mb-4">Types</label>
                            <select id="type" name="type" class="form-select py-3 rounded-3 shadow-sm" required>
                                <!-- Options will be populated here -->
                            </select>
                        </div>
                        <div class="col">
                            <label class="fw-bold  mb-4">Cities</label>
                            <select id="cities_name" name="cities_name" class="form-select py-3 rounded-3 shadow-sm" required>
                                <option value="">Select City</option>
                                @foreach($cities as $id => $name)
                                <option value="{{ $id }}" @if(old('cities_name')=='{{ $id }}' ) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
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
                            <!-- <h6>*Supported formats: PNG & JPG; File size limit: 2 MB</h6> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2.LOCATION -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5  rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">2.Location</h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4 d-flex justify-content-around">
                            <div class="col">
                                <label class="fw-bold mb-4 ">State</label>
                                <!-- <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                    <option value="">India</option>
                                    <option value="">London</option>
                                    <option value="">USA</option>
                                    <option value="">Korea</option>
                                </select> -->
                                <input type="text" placeholder="State" id="state" name="state" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('state')}}">
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4">City</label>
                                <!-- <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                    <option value="">State</option>
                                    <option value="Mr.">Tamilnadu</option>
                                    <option value="Mrs.">Kerala</option>
                                    <option value="Miss.">Bangalore</option>
                                </select> -->
                                <input type="text" placeholder="City" id="city" name="city" class="form-control py-3 rounded-3 shadow-sm" required value="{{old('city')}}">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="fw-bold mb-4 ">Address Details</label>
                            <div class="col-lg-12  ">
                                <input type="text" id="address" name="address" class="form-control py-3 rounded-3 shadow-sm" placeholder="Address" required value="{{old('address')}}">
                            </div>

                            <!-- <div class="col-lg-2 mt-3  ">
                                <i class="fa fa-search text-white  bg-dark  p-2 rounded-3" aria-hidden="true"></i>
                            </div> -->
                        </div>
                        <!-- <div class="row px-3 mb-4">
                            <div class="ratio ratio-16x9 ">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117996.95037632967!2d-74.05953969406828!3d40.75468158321536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2588f046ee661%3A0xa0b3281fcecc08c!2sManhattan%2C%20Nowy%20Jork%2C%20Stany%20Zjednoczone!5e1!3m2!1spl!2spl!4v1672242444695!5m2!1spl!2spl" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>

                        </div> -->

                        <div class="row mb-4">
                            <!-- <div class="col-lg-6">
                                <label class="fw-bold mb-4 " for="prefix">Zip Code</label>
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="3462" required>
                            </div> -->
                            <div class="col-lg-12">
                                <label class="fw-bold mb-4 ">Country</label>
                                <input type="text" id="country" name="country" class="form-control py-3 rounded-3 shadow-sm" placeholder="Country" required value="{{old('country')}}">
                            </div>
                            <div class="col mt-3">
                                <label class="fw-bold  mb-4">Geographical Features</label>
                                <select id="geo_feature" name="geo_feature" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select Geo</option>
                                    <option value="western_ghats" @if(old('property_type')=='western_ghats' ) selected @endif>Western Ghats</option>
                                    <option value="himalayas" @if(old('property_type')=='himalayas' ) selected @endif>Himalayas</option>
                                    <option value="chola_dynasty" @if(old('property_type')=='chola_dynasty' ) selected @endif>Chola Dynasty</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- 3.TOUR PLANNING  -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">3. Tour Planning</h4>
                    <div id="plan-container">
                        <!-- Initial Plan Item -->
                        <div class="plan-item mb-3">
                            <div class="row g-2 mt-5 d-flex justify-content-around">
                                <div class="col-lg-11">
                                    <input type="text" name="plan_title[]" id="plan_title" class="form-control py-3 rounded-3 shadow-sm" placeholder="Plan Title" required value="{{old('plan_title')}}">
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
                            <div class="mt-5">
                                <div class="row">
                                    <div class="col-lg-11">
                                        <label class="form-label form-label-top form-label-auto fw-bold mb-4">Plan Description</label>
                                        <textarea id="plan_description" class="container__textarea p-5 textarea-feild" name="plan_description[]" required></textarea>
                                    </div>
                                </div>
                            </div>
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
                                    <label class="fw-bold mb-4 ">Start date</label>
                                    <input type="date" class="form-control py-3 rounded-3 shadow-sm " name="start_date" id="start_date" value="{{old('start_date')}}" required>
                                </div>
                                <div class="col">
                                    <label class="fw-bold mb-4 ">Return Date</label>
                                    <input type="date" class="form-control py-3 rounded-3 shadow-sm " name="return_date" value="{{old('return_date')}}" id="return_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col">
                                <label class="fw-bold  mb-4">Tour duration</label>
                                <!-- <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                        <option value="">2-4 days tour</option>
                                        <option value="">3-5 days tour</option>
                                        <option value="">6-8 days tour</option>
                                        <option value="">2-4 days tour</option>
                                    </select> -->
                                <input type="text" class="form-control py-3 rounded-3 shadow-sm " id="total_days" name="total_days" value="{{old('total_days')}}" readonly>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                    changes</button></div>
                        </div> -->
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
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 ">Total Room</label>
                                <input type="text" id="total_room" name="total_room" class="form-control py-3 rounded-3 shadow-sm" placeholder="Total Room" required value="{{old('total_room')}}">
                            </div>
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 ">Member Capacity</label>
                                <input type="text" id="member_capacity" name="member_capacity" class="form-control py-3 rounded-3 shadow-sm" placeholder="Member Capacity" required value="{{old('member_capacity')}}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 ">Price</label>
                                <input type="text" id="price" name="price" class="form-control py-3 rounded-3 shadow-sm" placeholder="Price" value="{{old('price')}}" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 ">Coupon Code</label>
                                <input type="text" id="coupon_code" name="coupon_code" class="form-control py-3 rounded-3 shadow-sm" value="{{old('coupon_code')}}" placeholder="Code" required>
                            </div>

                            <!-- <label class="fw-bold mb-4 " for="prefix">Extra Price</label>

                            <div class="col-lg-3 mb-4">
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="Add Service Per Booking" required>
                            </div>
                            <div class="col-lg-3 mb-4">
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="Description" required>
                            </div>
                            <div class="col-lg-3 mb-4">
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="$27" required>
                            </div>

                            <div class="col-lg-1 mt-3 text-end">
                                <a href="#" class="table-link danger ">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </div>

                            <div class="col-lg-3">
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="Add Service Per Booking" required>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="Description" required>
                            </div>
                            <div class="col-lg-3">
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="$27" required>
                            </div>

                            <div class="col-lg-1 mt-3 text-end">
                                <a href="#" class="table-link danger ">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6.Camp rule -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 rounded-4 m-auto">
                    <h4 class="fw-bold mb-5">6. Camp Rule</h4>
                    <div class="mb-3">
                        <div id="camp-rule-container">
                            <div class="row g-2 mb-4 camp-rule-field">
                                <div class="col">
                                    <label class="fw-bold mb-4">Camp Rule</label>
                                    <input type="text" name="camp_rule[]" id="camp_rule" class="form-control py-3 rounded-3 shadow-sm" placeholder="Camp Rule" value="{{old('camp_rule')}}" required>
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
                    <h4 class="fw-bold mb-5">7.Important info</h4>
                    <div class="mb-3">
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <textarea id="important_info" class="container__textarea p-5 textarea-feild" name="important_info" value="{{old('important_info')}}" required></textarea>
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
                    <div class="row g-2 mt-3">
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


    document.addEventListener('DOMContentLoaded', function() {
        let planCount = 1; // Initialize with existing plan count

        // Function to clone the existing plan item
        function createPlanFields() {
            planCount++; // Increment plan count
            const container = document.getElementById('plan-container');
            const template = container.querySelector('.plan-item');

            // Clone the template
            const newPlan = template.cloneNode(true);
            // Update IDs and Names
            newPlan.querySelectorAll('input, textarea').forEach((field) => {
                field.name = field.name.replace(/\[\]$/, `[]`); // Ensure name is array-style
            });
            container.appendChild(newPlan);
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
                <input type="text" name="camp_rule[]" class="form-control py-3 rounded-3 shadow-sm" placeholder="Camp Rule" required>
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

    function updateTypeOptions() {
        // Get the selected value from the first dropdown
        var propCatValue = document.getElementById('prop_cat').value;

        // Get the container and the second dropdown
        var typeContainer = document.getElementById('type-container');
        var typeSelect = document.getElementById('type');

        // Define options based on the selected value
        var options = [];
        if (propCatValue === 'events') {
            options = [{
                    value: '',
                    text: 'Select Types'
                },
                {
                    value: 'upcoming_events',
                    text: 'Upcoming Events'
                },
                {
                    value: 'popular_events',
                    text: 'Popular Events'
                }
            ];
        } else if (propCatValue === 'packages') {
            options = [{
                    value: '',
                    text: 'Select Types'
                },
                {
                    value: 'upcoming_packages',
                    text: 'Upcoming Packages'
                },
                {
                    value: 'popular_packages',
                    text: 'Popular Packages'
                }
            ];
        }

        // Populate the options in the second dropdown
        typeSelect.innerHTML = ''; // Clear previous options
        options.forEach(function(option) {
            var opt = document.createElement('option');
            opt.value = option.value;
            opt.text = option.text;
            typeSelect.add(opt);
        });

        // Show or hide the second dropdown based on selection
        if (propCatValue) {
            typeContainer.style.display = 'block'; // Show container if category is selected
        } else {
            typeContainer.style.display = 'none'; // Hide container if no category is selected
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