@extends('components.layout')
@section('content')
    <div id="wrapper">
        @if (session('error'))
            <script>
                toastr.error('{{ session('error') }}')
            </script>
        @endif
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div>
                            Exams
                            @if ($course)
                                <span class="text-2xl font-normal"> - {{ $course->subject->name }}
                                    {{ $course->name }}</span>
                            @endif
                        </div>
                        @if (request()->query('courseId'))
                            <div class="inline"> <i class="fa-solid fa-file-circle-plus" data-bs-toggle="modal"
                                    data-bs-target="#addExamModal"></i> </div>
                        @endif
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                @include('exam.table')
                @include('exam.addModal')
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
