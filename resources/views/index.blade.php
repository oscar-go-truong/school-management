<x-layout>
    <div id="wrapper">
        @include('partials.topbar')
        <!-- /. NAV TOP  -->
        @include('partials.sidebar')
        <!-- /. NAV SIDE  -->

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
                <div>Hello <span class="font-medium text-green-500 text-2xl"> {{ Auth::User()->username }}! </span></div>
                <div>Email: <span class="font-medium  text-2xl"> {{ Auth::User()->email }} </span></div>
                <div>Fullname: <span class="font-medium  text-2xl"> {{ Auth::User()->fullname }} </span></div>
                <a href="{{ route('logout') }}">Log out</a>
            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
</x-layout>
