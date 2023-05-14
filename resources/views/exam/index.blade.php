@extends('components.layout')
@section('content')

    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif

    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div>
                Exams
                @if ($course)
                    <span class="text-2xl font-normal"> - {{ $course->subject->name }}
                        {{ $course->name }}</span>
                @endif
            </div>
            @hasanyrole('teacher|admin')
                @if (Request::get('courseId'))
                    <div class="inline btn btn-primary rounded pb-2" data-bs-toggle="modal" data-bs-target="#addExamModal">New <i
                            class="fa-solid fa-file-circle-plus"></i> </div>
                @endif
            @endhasanyrole
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('exam.table')
    @include('exam.addModal')
@endsection
