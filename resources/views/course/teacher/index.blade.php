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
                        <div> Teachers managerment</div>
                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                @include('course.teacher.table')

            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
