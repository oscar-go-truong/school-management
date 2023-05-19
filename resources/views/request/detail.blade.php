@extends('components.layout')
@section('content')
    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> Request - <span class="text-2xl font-normal">@yield('type-name')</span></div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    <div class="table-content">

        <div class="container mt-3">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('img/request.jpg') }}" alt="request-image" class="rounded">
                    <div class="request-info-box">
                    </div><!-- / request-info-box -->
                </div><!-- / column -->
                <div class="col-md">
                    <div class="request-info-box mt-0 mb-3">
                        <h5 class="pb-1"><b>REQUEST DETAILS</b></h5>

                    </div><!-- / request-info-box -->

                    @yield('request-content')
                    @if ($request->status === $status['PENDING'])
                        @role('admin')
                            <div id="btns">
                                <button type="button" class="btn bg-success" id="approve"
                                    data-id="{{ $request->id }}">Aprrove</button>
                                <button type="button" class="btn bg-danger" id="reject"
                                    data-id="{{ $request->id }}">Reject</button>
                            </div>
                        @else
                            <div class="bg-primary text-white text-center"> Pending</div>
                        @endrole
                    @elseif ($request->status === $status['APPROVED'])
                        <div class="bg-success text-white text-center"> Approved</div>
                    @elseif ($request->status === $status['REJECTED'])
                        <div class="bg-danger text-white text-center">Rejected</div>
                    @elseif ($request->status === $status['CANCELED'])
                        <div class="bg-danger text-white text-center">Canceled</div>
                    @endif
                </div>


            </div>
        </div>

    </div>
    <script>
        const reject = (id) => {
            toastr.info('Rejecting request!');
            $.ajax({
                type: "PATCH",
                url: `/requests/${id}/reject`,
                data: null,
                dataType: "json",
                success: function(resp) {
                    if (resp.data) {
                        toastr.success(resp.message);
                        $('#btns').html('<div class="bg-danger text-white text-center">Rejected</div>');
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function() {
                    toastr.error('Error, please try again later!');
                }
            });
        }

        const approve = (id) => {
            toastr.info('approving request!');
            $.ajax({
                type: "PATCH",
                url: `/requests/${id}/approve`,
                data: null,
                dataType: "json",
                success: function(resp) {
                    if (resp.data) {
                        toastr.success(resp.message);
                        $('#btns').html('<div class="bg-success text-white text-center"> Approved</div>');
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function() {
                    toastr.error('Error, please try again later!');
                }
            });
        }
        $(document).ready(function() {
            $('#reject').click(function() {
                const id = $(this).data('id');
                toastr.clear();
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.options.closeButton = true;
                toastr.warning(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to reject this request!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-secondary mr-3">No</button> 
                        <button class="btn btn-danger ml-3" onclick='reject(${id})'>Yes</button></div>
                    </div>`);
                toastr.options.timeOut = 3000;
                toastr.options.extendedTimeOut = 3000;
            })

            $('#approve').click(function() {
                const id = $(this).data('id');
                toastr.clear();
                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.options.closeButton = true;
                toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to approve this request!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-warning mr-3">No</button> 
                        <button class="btn btn-success ml-3" onclick='approve(${id})'>Yes</button></div>
                    </div>`);
                toastr.options.timeOut = 3000;
                toastr.options.extendedTimeOut = 3000;
            })
        })
    </script>
@endsection
