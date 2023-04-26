@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div> Course details</div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content">

                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('img/course.png') }}" alt="course-image" class="rounded">
                                <div class="course-info-box">
                                </div><!-- / course-info-box -->
                            </div><!-- / column -->
                            <div class="col-md">
                                <div class="course-info-box mt-0 mb-3">
                                    <h5 class="pb-1"><b>COURSE DETAILS</b></h5>
                                    <p>{{ $course->descriptions }}</p>
                                </div><!-- / course-info-box -->

                                <div class="course-info-box">
                                    <p><b>Subject: </b> {{ $course->subject->name }}</p>
                                    <p><b>Homeroom Teacher: </b>{{ $course->homeroomTeacher->fullname }}</p>
                                    <p><b>Date: </b> {{ $course->created_at }}</p>
                                    <p><b>Status: </b>
                                        <span class="{{ $course->status === 1 ? 'text-success' : 'text-danger' }}">
                                            {{ $course->status === 1 ? 'Active' : 'Inactive' }}
                                    </p>
                                    </span>
                                </div>
                            </div><!-- / column -->


                        </div>
                    </div>

                </div>

            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
