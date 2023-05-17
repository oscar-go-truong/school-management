@extends('components.layout')
@section('content')

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
                        <p><b>Name: </b> {{ $course->name }}</p>
                        <p><b>Subject: </b> {{ $course->subject->name }}</p>
                        <p><b>Homeroom Teacher: </b>{{ $course->homeroomTeacher->fullname }}</p>
                        <p><b>Date: </b> {{ $course->created_at }}</p>
                        <p><b>Status: </b>
                            <span class="{{ $course->status === 1 ? 'text-success' : 'text-danger' }}">
                                {{ $course->status === 1 ? 'Active' : 'Inactive' }}
                        </p>
                        </span>
                    </div>
                    @if ($course->status === 1)
                        <div id="btns">
                            @hasanyrole('admin|teacher')
                                <button type="button" class="btn bg-info" id="export">Export student list
                                </button>
                                <button type="button" class="btn bg-success" data-bs-toggle="modal"
                                    data-bs-target="#addExamModal">New exam
                                </button>
                                @include('course.addExamModal')
                            @endhasanyrole
                            @role('student')
                                @if ($course->isRequestSwitch)
                                    <div class="text-white bg-primary p-2">Requesting to change course...
                                    </div>
                                @else
                                    <div id="switch-course-btn">
                                    </div>
                                    <div id="modal-trigger">
                                        <button type="button" class="btn bg-primary" data-bs-toggle="modal"
                                            data-bs-target="#switchCourseModal">Switch
                                            course</button>
                                        @include('course.switchCourseModal')
                                    </div>
                                @endif
                            @endrole
                        </div>
                    @endif
                </div><!-- / column -->
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#export').click(function() {
                const id = '{{ $course->id }}';
                toastr.clear();
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0
                toastr.options.closeButton = true;
                toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to export students list!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-secondary mr-3">No</button> 
                        <a href="/courses/${id}/students-list/export"><button class="btn btn-success ml-3" '>Yes</button></div><a/>
                    </div>`);
            })
        })
    </script>
@endsection
