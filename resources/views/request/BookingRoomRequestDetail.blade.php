@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div> Request - <span class="text-2xl font-normal">Booking room</span></div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content">

                    <div class="container mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ asset('img/request.jpg') }}" alt="course-image" class="rounded">
                                <div class="course-info-box">
                                </div><!-- / course-info-box -->
                            </div><!-- / column -->
                            <div class="col-md">
                                <div class="course-info-box mt-0 mb-3">
                                    <h5 class="pb-1"><b>REQUEST DETAILS</b></h5>

                                </div><!-- / course-info-box -->

                                <div class="course-info-box">
                                    <p><b>User request name: </b> {{ $request->userRequest->fullname }}</p>
                                    <p><b>User request email: </b> {{ $request->userRequest->email }}</p>
                                    @if ($request->status === $status['APPROVED'])
                                        <p><b>User approve name: </b> {{ $request->userApprove->fullname }}</p>
                                        <p><b>User approve email: </b> {{ $request->userApprove->email }}</p>
                                    @endif
                                    <p><b>Room: </b> {{ $content->room->name }}</p>
                                    <p><b>From : </b> {{ $content->booking_date_start }}</p>
                                    <p><b>To : </b> {{ $content->booking_date_finish }}</p>
                                </div>
                                @if ($request->status === $status['PENDING'])
                                    <button type="button" class="btn bg-success">Aprrove</button>
                                    <button type="button" class="btn bg-danger">Reject</button>
                                @elseif ($request->status === $status['APPROVED'])
                                    <div class="bg-success text-white text-center"> Approved</div>
                                @elseif ($request->status === $status['REJECTED'])
                                    <div class="bg-danger text-white text-center">Rejected</div>
                                @endif
                            </div>


                        </div>
                    </div>

                </div>

            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
