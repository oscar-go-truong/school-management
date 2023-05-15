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
                    <div class="subject-info-box mt-0 mb-3">
                        <h5 class="pb-1"><b>SUBJECT DETAILS</b></h5>
                        <p>{{ $subject->descriptions }}</p>
                    </div><!-- / subject-info-box -->

                    <div class="subject-info-box">
                        <p><b>Date: </b> {{ $subject->created_at }}</p>
                        <p><b>Status: </b>
                            <span class="{{ $subject->status === 1 ? 'text-success' : 'text-danger' }}">
                                {{ $subject->status === 1 ? 'Active' : 'Inactive' }}
                        </p>
                        </span>
                    </div>
                </div><!-- / column -->


            </div>
        </div>

    </div>
@endsection
