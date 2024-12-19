@extends('layouts.app')
@Section('content')
<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;Add Package</h3>
    </div>

    <!-- FORM -->
    <form id="registrationForm" action="submit.php" method="POST" autocomplete="off">
        <div class="row mb-5">
            <div class="col">

                <!-- INFORMATION -->
                <div class="form-body px-5 py-5 rounded-4 m-auto ">

                    <h4 class="fw-bold mb-5">1.Information </h4>

                    <!-- Attendee Information Section -->
                    <div class="mb-3">

                        <div class="row g-2 mb-4">

                            <div class="col">
                                <label class="fw-bold mb-4 " for="prefix">Enter your tittle</label>
                                <input type="text" placeholder="Switzerland city tour" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" required>
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4" for="prefix">Tour Category</label>
                                <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                    <option value="">Category</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Miss.">Miss.</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- </form> -->

                    <!-- Contact Information Section -->
                    <div class="row g-2 mb-5">

                        <div class="col">
                            <label class="fw-bold  mb-4" for="contactNumber">Enter Keyword</label>
                            <input type="tel" id="contactNumber" name="contactNumber" class="form-control py-3 rounded-3 shadow-sm" placeholder="Keyword" required>
                        </div>
                    </div>

                    <!-- Guest Information Section -->

                    <!-- Description -->
                    <div class="row g-2">

                        <div class="col">
                            <label class="form-label form-label-top form-label-auto fw-bold  mb-4" id="label_textarea" for="textarea_input">Additional Comments</label>
                            <textarea id="textarea" class="container__textarea"></textarea>

                            <!-- UPLOAD IMG -->

                            <label class="fw-bold  mb-4" for="contactNumber">Upload Photo</label>

                            <div class="row d-flex mb-4">
                                <div class="col-lg-2">
                                    <div class="form-input">
                                        <label for="file-ip-1" class="px-4 py-3 text-center">
                                            <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpace_addpic_icon.svg">
                                            <p class="text-center  fw-light mt-3"> Add Pic</p>

                                        </label>
                                        <input type="file" name="img_one" id="file-ip-1" accept="image/*" onchange="showPreview(event, 1);">
                                    </div>

                                </div>
                                <div class="col-lg-2">
                                    <div class="form-input">
                                        <label for="file-ip-1" class="px-4 py-3 text-center">
                                            <img class="text-center mt-3 " id="file-ip-1-preview" src="/assets/image/dashboard/innerpace_addpic_icon.svg">
                                            <p class="text-center  fw-light mt-3"> Add Pic</p>
                                        </label>
                                        <input type="file" name="img_one" id="file-ip-1" accept="image/*" onchange="showPreview(event, 1);">
                                    </div>

                                </div>
                                <div class="col-lg-2">
                                    <div class="form-input">
                                        <label for="file-ip-1" class="px-4 py-3 text-center">
                                            <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpace_addpic_icon.svg">
                                            <p class="text-center  fw-light mt-3"> Add Pic</p>

                                        </label>
                                        <input type="file" name="img_one" id="file-ip-1" accept="image/*" onchange="showPreview(event, 1);">
                                    </div>

                                </div>
                                <div class="col-lg-2">
                                    <div class="form-input">
                                        <label for="file-ip-1" class="px-4 py-3 text-center">
                                            <img class="text-center mt-3" id="file-ip-1-preview" src="/assets/image/dashboard/innerpace_addpic_icon.svg">
                                            <p class="text-center  fw-light mt-3"> Add Pic</p>

                                        </label>
                                        <input type="file" name="img_one" id="file-ip-1" accept="image/*" onchange="showPreview(event, 1);">
                                    </div>

                                </div>

                            </div>
                            <h6>*Supported <strong>Png</strong> & Jpg Not more than 2 Mb</h6>


                            <!-- UPLOAD IMG WND -->



                        </div>
                    </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>


        <!-- TOUR PLANNING  -->
        <div class="row mb-5">
            <div class="col">


                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <!-- <form id="registrationForm" action="submit.php" method="POST"> -->
                    <h4 class="fw-bold mb-5">2.Tour Planning</h4>

                    <!-- Attendee Information Section -->
                    <div class="mb-3">

                        <div class="row g-2 mb-4 d-flex justify-content-around">

                            <div class="col-lg-11  ">

                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="Enter Tittle" required>
                            </div>

                            <div class="col-lg-1 mt-3 text-end">
                                <a href="#" class="table-link danger ">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class=" mt-5">
                            <div class="row">
                                <div class="col-lg-12 ">
                                    <div id="summernote"></div>
                                </div>
                            </div>
                        </div>




                    </div>
                    <div class="text-end "> <button class="btn-add  rounded border-0 px-5 py-3 text-end text-white"><i class="fa fa-plus" aria-hidden="true"></i>
                            Add</button></div>

                </div>









                <!-- </form> UPLOAD IMG WND -->



            </div>
        </div>


        <!-- LOCCATION -->
        <div class="row mb-5">
            <div class="col">


                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <!-- <form id="registrationForm" action="submit.php" method="POST"> -->
                    <h4 class="fw-bold mb-5">3.Location</h4>

                    <!-- Attendee Information Section -->
                    <div class="mb-3">

                        <div class="row g-2 mb-4 d-flex justify-content-around">

                            <div class="col">
                                <label class="fw-bold mb-4 " for="prefix">Select City</label>
                                <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                    <option value="">India</option>
                                    <option value="">London</option>
                                    <option value="">USA</option>
                                    <option value="">Korea</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="fw-bold  mb-4" for="prefix">Select State</label>
                                <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                    <option value="">State</option>
                                    <option value="Mr.">Tamilnadu</option>
                                    <option value="Mrs.">Kerala</option>
                                    <option value="Miss.">Bangalore</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">

                            <label class="fw-bold mb-4 " for="prefix">Address Details</label>
                            <div class="col-lg-10  ">

                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="2464 Royal Ln. Mesa, New Jersey 45463" required>
                            </div>

                            <div class="col-lg-2 mt-3  ">
                                <!-- <i class="bi bi-search text-white  p-2 rounded-3"></i> -->
                                <i class="fa fa-search text-white  bg-dark  p-2 rounded-3" aria-hidden="true"></i>
                            </div>


                        </div>
                        <div class="row px-3 mb-4">
                            <div class="ratio ratio-16x9 ">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d117996.95037632967!2d-74.05953969406828!3d40.75468158321536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2588f046ee661%3A0xa0b3281fcecc08c!2sManhattan%2C%20Nowy%20Jork%2C%20Stany%20Zjednoczone!5e1!3m2!1spl!2spl!4v1672242444695!5m2!1spl!2spl" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>

                        </div>

                        <div class="row mb-4">


                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 " for="prefix">Zip Code</label>
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="3462" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 " for="prefix">Country</label>
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="United Kingdom" required>
                            </div>
                        </div>
                        <div class="row">
                            <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                    changes</button></div>
                        </div>
                    </div>
                </div>









                <!-- UPLOAD IMG WND -->



            </div>
        </div>




        <!-- AMENITIES -->

        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">4.Amenities</h4>
                    <div class="row mb-4">
                        <div class="col-lg-3">


                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>


                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                changes</button></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOD & BEVERAGES -->
        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">5.Food and Beverages</h4>
                    <div class="row mb-4">
                        <div class="col-lg-3">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                changes</button></div>
                    </div>
                </div>
            </div>

        </div>


        <!-- PRICING -->
        <div class="row mb-5">
            <div class="col">


                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <!-- <form id="registrationForm" action="submit.php" method="POST"> -->
                    <h4 class="fw-bold mb-5">6.Pricing</h4>

                    <!-- Attendee Information Section -->
                    <div class="mb-3">
                        <div class="row mb-4">

                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 " for="prefix">Tour Price</label>
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="$3215" required>
                            </div>
                            <div class="col-lg-6">
                                <label class="fw-bold mb-4 " for="prefix">Tour Price</label>
                                <input type="text" id="firstName" name="firstName" class="form-control py-3 rounded-3 shadow-sm" placeholder="$3215" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="fw-bold mb-4 " for="prefix">Extra Price</label>

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
                            </div>
                        </div>




                    </div>
                </div>
                <!-- UPLOAD IMG WND -->



            </div>
        </div>


        <!-- TOUR DATE & TIME -->
        <div class="row mb-5">
            <div class="col">


                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <!-- <form id="registrationForm" action="submit.php" method="POST"> -->
                    <h4 class="fw-bold mb-5">7.Tour date & Time</h4>

                    <!-- Attendee Information Section -->
                    <div class="mb-3">
                        <div class="row mb-4">

                            <div class="row g-2 mb-4">

                                <div class="col">
                                    <label class="fw-bold  mb-4" for="prefix">Tour duration</label>
                                    <select id="prefix" name="prefix" class="form-select py-3 rounded-3 shadow-sm">
                                        <option value="">2-4 days tour</option>
                                        <option value="">3-5 days tour</option>
                                        <option value="">6-8 days tour</option>
                                        <option value="">2-4 days tour</option>
                                    </select>
                                </div>

                                <div class="col">
                                    <label class="fw-bold mb-4 " for="prefix">Start date</label>
                                    <input type="date" class="form-control py-3 rounded-3 shadow-sm " id="date">

                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">

                            <div class="col">
                                <label class="fw-bold mb-4 " for="prefix">Return Date</label>
                                <div class="col-5">
                                    <input type="date" class="form-control py-3 rounded-3 shadow-sm " id="date">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                    changes</button></div>
                        </div>
                    </div>
                </div>

                <!-- UPLOAD IMG WND -->



            </div>
        </div>

        <!-- ACTIVITIES -->

        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">8.Activities</h4>
                    <div class="row mb-4">
                        <div class="col-lg-3">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="alarm" name="services[]" value="alarm">
                                <label class="form-check-label" for="alarm">Alarm System</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="navigation" name="services[]" value="navigation">
                                <label class="form-check-label" for="navigation">Navigation</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="window" name="services[]" value="window">
                                <label class="form-check-label" for="window">Window Coverings</label>
                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                changes</button></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- SAFETY FEATURES  -->

        <div class="row mb-5">
            <div class="col">
                <div class="form-body px-5 py-5 rounded-4 m-auto ">
                    <h4 class="fw-bold mb-5">9.Safety Features</h4>
                    <div class="row mb-4">
                        <div class="col-lg-3">

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>


                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="laundry" name="services[]" value="laundry">
                                <label class="form-check-label" for="laundry">Laundry Service</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="food" name="services[]" value="food">
                                <label class="form-check-label" for="food">Food & Drinks</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="pool" name="services[]" value="pool">
                                <label class="form-check-label" for="pool">Swimming Pool</label>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div> <button class="btn-add  rounded-3 border-0 px-3 py-2  text-white"> Save
                                changes</button></div>
                    </div>
                </div>
            </div>

        </div>

    </form>

</div>
@endsection