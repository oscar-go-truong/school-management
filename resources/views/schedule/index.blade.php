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
                <div class="container">
                    <div class="response"></div>
                    <div id='calendar'></div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                editable: true,
                displayEventTime: true,
                editable: true,
            });
        })
    </script>
@endsection
