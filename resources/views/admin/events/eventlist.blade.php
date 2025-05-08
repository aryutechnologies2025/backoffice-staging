@extends('layouts.app')
@Section('content')

<div class="row body-sec py-5  px-5 justify-content-around">
    <div class="col-lg-6">
        <h3 class="fw-bold"><span class="vr"></span>{{$title}}</h3>
    </div>
    <div class="col-lg-6">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.event_add_form') }}">
                <button class="btn btn-add px-5" type="button"> Add Events </button>
            </a>
        </div>
    </div>
</div>

<!-- EVENT LIST -->
<div class="row body-sec px-5">
    <div class="col-lg-12">
        <div class="table-sec rounded-bottom-4 shadow-sm mb-5">
            <table class="table user-list">
                <thead>
                    <tr class="rounded-top-4">
                        <th><span> Image </span></th>
                        <th class="text-center"><span> Title </span></th>
                        <th class="text-center"><span> Location </span></th>
                        <th class="text-center"><span>Price</span></th>
                        <th class="text-center"><span>Guests</span></th>
                        <th class="text-center"><span>Action</span></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 1 -->
                    <tr>
                        <td class="d-flex ">
                            <img src="/assets/image/dashboard/innerpece_northpalces_img.svg" alt="">
                            <div class="p-2">
                                <p class="p-0 lh-1 text-secondary">ID: #4435</p>
                                <h6 class="fw-bold">Venenatis eget</h6>
                                <h6><span class="text-danger">$27,000</span></h6>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-live px-5" type="button">Live</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Maybe</a></li>
                                    <li><a class="dropdown-item" href="#">Whatever</a></li>

                                </ul>
                            </div>
                        </td>
                        <td class="text-center">
                            <h6 class="label label-default text-center mt-2">11 Apr 2024</h6>
                        </td>
                        <td>
                            <h6 class="label label-default text-center mt-2">11 Apr 2024</h6>
                        </td>
                        <td>
                            <h6 class="label label-default text-center mt-2">03 People</h6>
                        </td>
                        <td class="text-center" style="width: 20%;">
                            <a href="#" class="table-link">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-pencil fa-stack-1x fa-inverse" style="color: red !important;"></i>
                                </span>
                            </a>
                            <a href="#" class="table-link danger">
                                <span class="fa-stack">
                                    <!-- <i class="fa fa-square fa-stack-2x"></i> -->
                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse" style="color: red !important;"></i>
                                </span>
                            </a>
                        </td>
                    </tr>

                    

                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-sec ">
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link rounded-circle text-dark fw-bold" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link rounded-circle text-dark fw-bold px-3 ms-2" href="#">1</a>
                    </li>
                    <li class="page-item"><a class="page-link rounded-circle text-dark fw-bold ms-2" href="#">2</a>
                    </li>
                    <li class="page-item"><a class="page-link rounded-circle text-dark fw-bold ms-2" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link rounded-circle text-dark fw-bold ms-2" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection