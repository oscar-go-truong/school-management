@extends('components.layout')
@section('content')
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Subject details</div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    <div class="table-content">

        <div class="container mt-3">
            <div class="row">
                <div class="col-xl-4">
                    <img src="{{ asset('img/subject.jpg') }}" alt="subject-image" class="rounded">
                    <div class="subject-info-box">
                    </div><!-- / subject-info-box -->
                </div><!-- / column -->
                <div class="col-xl-8">
                    <div class="text-dark mt-0 mb-3">
                        <h5 class="pb-1"><b>{{ strtoupper($subject->name) }}</b></h5>
                        <div>{{ $subject->descriptions }}</div>
                    </div><!-- / subject-info-box -->
                    <a href="/courses?subjectId={{ $subject->id }}" class="detail-subject">
                        <h3 class="font-bold mt-1 mb-1">Active courses:</h3>
                    </a>
                    <div class="max-h-80">
                        @foreach ($courses as $course)
                            <div class="border-b-2 border-gray-500">{{ $course->name }}</div>
                        @endforeach
                    </div>
                </div><!-- / column -->


            </div>
        </div>

    </div>
@endsection
