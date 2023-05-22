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
                <div class="col-xl-4">
                    <img src="{{ asset('img/course.png') }}" alt="course-image" class="rounded">
                    <div class="course-info-box">
                    </div><!-- / course-info-box -->
                </div><!-- / column -->
                <div class="col-xl-8">
                    <div class="course-info-box mt-0 mb-3">
                        <h5 class="pb-1"><b>{{ strtoupper($course->subject->name . ' ' . $course->name) }}</b></h5>
                        <div>{{ $course->descriptions }}</div>
                        <div class="text-sm"><b>Homeroom Teacher: </b>{{ $course->homeroomTeacher->fullname }}</div>
                    </div><!-- / course-info-box -->
                    <div class="course-info-box mb-3">
                        <div class="font-bold mb-1"> Schedule: </div>
                        @foreach ($course->schedules as $schedule)
                            <div>{{ $schedule->weekday }} {{ $schedule->start_time }}-{{ $schedule->finish_time }}</div>
                        @endforeach
                        <div class="font-bold mb-1 mt-2"> Detail: </div>
                        <a class="d-flex justify-content-between border-b-2 borbder-gray-2 bg-cyan-500 text-white course-detail p-1"
                            href="/courses/{{ $course->id }}/teachers">
                            <div class="inline font-bold">Teacher</div>
                            <div class="inline font-bold mr-2">{{ $course->teachers_count }}</div>
                        </a>
                        <a class="d-flex justify-content-between border-b-2 borbder-gray-2 bg-cyan-500 text-white course-detail p-1"
                            href="/courses/{{ $course->id }}/students">
                            <div class="inline font-bold">Student</div>
                            <div class="inline font-bold mr-2">{{ $course->students_count }}</div>
                        </a>
                        <a class="d-flex justify-content-between border-b-2 borbder-gray-2 bg-cyan-500 text-white course-detail p-1"
                            href="/exams?courseId={{ $course->id }}">
                            <div class="inline font-bold">Exam</div>
                            <div class="inline font-bold mr-2">{{ $course->exams_count }}</div>
                        </a>

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
