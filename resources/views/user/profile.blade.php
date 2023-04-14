@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">

                    <div class="col-md-12 text-3xl font-bold">
                        Profile
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-5" />
                <!-- /. ROW  -->
                <div class="blank-content">
                    <div>Hello <span class="font-medium text-green-500 text-2xl"> {{ $user->username }}! </span></div>
                    <div>Email: <span class="font-medium  text-2xl"> {{ $user->email }} </span></div>
                    <div>Fullname: <span class="font-medium  text-2xl"> {{ $user->fullname }} </span></div>
                    <a href="{{ route('logout') }}">Log out</a>
                </div>
            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
