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
                <div class="col-md-4">
                    <img src="{{ asset('img/subject.jpg') }}" alt="subject-image" class="rounded">
                    <div class="subject-info-box">
                    </div><!-- / subject-info-box -->
                </div><!-- / column -->
                <div class="col-md">
                    <div class="text-dark mt-0 mb-3">
                        <h5 class="pb-1"><b>SUBJECT DETAILS</b></h5>
                        <div>{{ $subject->descriptions }}</div>
                    </div><!-- / subject-info-box -->
                    <div class="subject-info-box">
                        <div><b>Name: </b> {{ $subject->name }}</div>
                        <div><b>Date: </b> {{ $subject->date }}</div>
                        <div><b>Status: </b>
                            <span class="{{ $subject->status === 1 ? 'text-success' : 'text-danger' }}">
                                {{ $subject->status === 1 ? 'Active' : 'Inactive' }}
                        </div>
                        </span>
                    </div>
                </div><!-- / column -->


            </div>
        </div>

    </div>
@endsection
