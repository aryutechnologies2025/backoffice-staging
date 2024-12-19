@extends('layouts.app')
@Section('content')

<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-12">
        <h3 class="fw-bold"><span class="vr"></span>&nbsp;{{$title}}</h3>
    </div>

    <div class="row mb-5">
        <div class="col">
            <div class="form-body px-5 py-5 rounded-4 m-auto ">
                <form id="form_valid" action="{{ route('admin.themes_cat_update', ['id'=>$themes_cat_details->id]) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <div class="g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4">Cities <span class="text-danger">*</span></label>
                                <select id="themes_name" name="themes_name" class="form-select py-3 rounded-3 shadow-sm" required>
                                    <option value="">Select City</option>
                                    @foreach($themes as $id => $name)
                                    <option value="{{ $id }}" {{ $id == $selectedThemeId ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col">
                                <label class="fw-bold mb-4 "> Theme Category </label>
                                <input type="text" placeholder="Theme Category" id="theme_cat" name="theme_cat" class="form-control py-3 rounded-3 shadow-sm" required value="{{$themes_cat_details->theme_cat}}">
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col">
                                <label class="fw-bold ">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input check_bx" type="checkbox" id="status" name="status" {{ $themes_cat_details->status ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-end mt-5">
                        <a href="{{ route('admin.themes_cat_list') }}">
                            <button type="button" class="cancel-btn"> Cancel </button>
                        </a>
                        <button class="submit-btn sbmtBtn ms-4"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection