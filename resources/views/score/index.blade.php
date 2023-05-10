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
                        <div> Scores<span class="text-2xl font-normal"> - {{ $exam->course->subject->name }}
                                {{ $exam->course->name }} - {{ $exam->type }} exams</span>
                        </div>
                        <div class="inline">
                            <button class="btn btn-primary rounded pb-2" data-bs-toggle="modal"
                                data-bs-target="#uploadFileModal">Upload <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                @include('score.table')
                @include('score.uploadFileModal')
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
