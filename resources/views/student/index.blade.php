@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Students <span class="text-2xl font-normal"> - {{ $course->subject->name }}
                    {{ $course->name }}</span></div>
            @role('admin')
                @if ($course->status === 1)
                    <div class="inline btn btn-primary rounded pb-2" data-bs-toggle="modal" data-bs-target="#addStudentModal"> New
                        <i class="fa-solid fa-user-plus"></i> </div>
                @endif
            @endrole
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    @include('student.table')
    @include('student.addModal')
@endsection
