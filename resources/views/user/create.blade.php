@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold">
                        User managerment - Create user
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="blank-content">
                    <form class="container">
                        <div class="form-group mt-3">
                            <label for="exampleInputEmail1" class="font-bold mb-1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                placeholder="Enter email">
                        </div>
                        <div class="form-group mt-3">
                            <label for="Username" class="font-bold mb-1">Username</label>
                            <input type="text" class="form-control" id="Username" placeholder="Username">
                        </div>

                        <div class="form-group mt-3">
                            <label for="exampleInputPassword1" class="font-bold mb-1">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                        </div>
                        <div class="form-group mt-3">
                            <label for="exampleInputPassword1" class="font-bold mb-1">Confirm password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1"
                                placeholder="Confirm password">
                        </div>
                        <div class="form-group mt-3">
                            <label for="fullname" class="font-bold mb-1">Fullname</label>
                            <input type="text" class="form-control" id="fullname" placeholder="">
                        </div>
                    </form>
                </div>

            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
@endsection
