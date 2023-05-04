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
                        <div> Students <span class="text-2xl font-normal"> - {{ $course->subject->name }}
                                {{ $course->name }}</span></div>
                        @if (Auth::user()->isAdministrator())
                            <div class="inline text-gray-400"> <i class="fa-solid fa-user-plus" data-bs-toggle="modal"
                                    data-bs-target="#addStudentModal"></i> </div>
                        @endif
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                @include('student.table')
                @include('student.addModal')
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
