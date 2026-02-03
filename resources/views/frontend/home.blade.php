@extends('frontend.layouts.app')


@section('main')
<!-- start hero section -->
            <section class="section job-hero-section bg-light pb-0" id="hero">
                <div class="container">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-lg-6">
                            <div>
                                <h1 class="display-6 fw-semibold text-capitalize mb-3 lh-base">Find your next job and build your dream here</h1>
                                <p class="lead text-muted lh-base mb-4">Find jobs, create trackable resumes and enrich your applications. Carefully crafted after analyzing the needs of different industries.</p>
                                <form action="#" class="job-panel-filter">
                                    <div class="row g-md-0 g-2">
                                        <div class="col-md-4">
                                            <div>
                                                <input type="search" id="job-title" class="form-control filter-input-box" placeholder="Job, Company name...">
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-4">
                                            <div>
                                                <select class="form-control" data-choices>
                                                    <option value="">Select job type</option>
                                                    <option value="Full Time">Full Time</option>
                                                    <option value="Part Time">Part Time</option>
                                                    <option value="Freelance">Freelance</option>
                                                    <option value="Internship">Internship</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-md-4">
                                            <div class="h-100">
                                                <button class="btn btn-primary submit-btn w-100 h-100" type="submit"><i class="ri-search-2-line align-bottom me-1"></i> Find Job</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end row-->
                                </form>

                                <ul class="treding-keywords list-inline mb-0 mt-3 fs-13">
                                    <li class="list-inline-item text-danger fw-semibold"><i class="mdi mdi-tag-multiple-outline align-middle"></i> Trending Keywords:</li>
                                    <li class="list-inline-item"><a href="javascript:void(0)">Design,</a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)">Development,</a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)">Manager,</a></li>
                                    <li class="list-inline-item"><a href="javascript:void(0)">Senior</a></li>
                                </ul>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-lg-4">
                            <div class="position-relative home-img text-center mt-5 mt-lg-0">
                                <div class="card p-3 rounded shadow-lg inquiry-box">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0 me-3">
                                            <div class="avatar-title bg-warning-subtle text-warning rounded fs-18">
                                                <i class="ri-mail-send-line"></i>
                                            </div>
                                        </div>
                                        <h5 class="fs-15 lh-base mb-0">Work Inquiry from velzon</h5>
                                    </div>
                                </div>

                                <div class="card p-3 rounded shadow-lg application-box">
                                    <h5 class="fs-15 lh-base mb-3">Applications</h5>
                                    <div class="avatar-group">
                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                            <div class="avatar-xs">
                                                <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-fluid">
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded-circle bg-danger">
                                                    S
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Ellen Smith">
                                            <div class="avatar-xs">
                                                <img src="assets/images/users/avatar-10.jpg" alt="" class="rounded-circle img-fluid">
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded-circle bg-success">
                                                    Z
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Brent Gonzalez">
                                            <div class="avatar-xs">
                                                <img src="assets/images/users/avatar-9.jpg" alt="" class="rounded-circle img-fluid">
                                            </div>
                                        </a>
                                        <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="More Appliances">
                                            <div class="avatar-xs">
                                                <div class="avatar-title fs-13 rounded-circle bg-light border-dashed border text-primary">
                                                    2k+
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <img src="assets/images/job-profile2.png" alt="" class="user-img">

                                <div class="circle-effect">
                                    <div class="circle"></div>
                                    <div class="circle2"></div>
                                    <div class="circle3"></div>
                                    <div class="circle4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </section>
            <!-- end hero section -->
        @endsection
