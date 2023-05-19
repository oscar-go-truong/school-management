@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}')
        </script>
    @endif
    <div class="container">
        <div class="blank-content">

            <!-- Breadcrumb -->

            <!-- /Breadcrumb -->

            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin"
                                    class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4>{{ $user->fullname }}</h4>
                                    <p class="text-secondary mb-1">{{ $user->getRoleNames()[0] }}</p>
                                    <a class="btn btn-outline-primary" href='{{ route('logout') }}'>Log out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="col-md-8" method="POST" action="/profile/update" id="update-profile-form">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Username</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->username }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Fullname</h6>
                                </div>
                                <input class="col-sm-9" name="fullname" id="fullname" value="{{ $user->fullname }}" />
                                @error('fullname')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <input class="col-sm-9" name="phone" id="phone" value="{{ $user->phone }}" />
                                @error('phone')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Mobile</h6>
                                </div>
                                <input class="col-sm-9" name="mobile" id="mobile" value="{{ $user->mobile }}" />
                                @error('mobile')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Address</h6>
                                </div>
                                <input class="col-sm-9" name="address" id="address" value="{{ $user->address }}" />
                                @error('address')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" id="update">Update</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gutters-sm">
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i
                                            class="material-icons text-info mr-2">assignment</i>Project Status
                                    </h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%"
                                            aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%"
                                            aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%"
                                            aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%"
                                            aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="d-flex align-items-center mb-3"><i
                                            class="material-icons text-info mr-2">assignment</i>Project Status
                                    </h6>
                                    <small>Web Design</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"
                                            aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Website Markup</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 72%"
                                            aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>One Page</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 89%"
                                            aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Mobile Template</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 55%"
                                            aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small>Backend API</small>
                                    <div class="progress mb-3" style="height: 5px">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 66%"
                                            aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </form>
            </div>

        </div>
        <script>
            const validate = (fullname, phone, mobile, address) => {
                if (!fullname || !phone || !mobile || !address) {
                    if (!fullname) {
                        toastr.warning('Fullname is required!');
                        $('#fullname').removeClass('is-valid');
                        $('#fullname').addClass('is-invalid');
                    } else {
                        $('#fullname').removeClass('is-invalid');
                        $('#fullname').addClass('is-valid');
                    }

                    if (!phone) {
                        toastr.warning('Phone number is required!');
                        $('#phone').removeClass('is-valid');
                        $('#phone').addClass('is-invalid');
                    } else {
                        $('#phone').removeClass('is-invalid');
                        $('#phone').addClass('is-valid');
                    }

                    if (!mobile) {
                        toastr.warning('Mobile is required!');
                        $('#mobile').removeClass('is-valid');
                        $('#mobile').addClass('is-invalid');
                    } else {
                        $('#mobile').removeClass('is-invalid');
                        $('#mobile').addClass('is-valid');
                    }

                    if (!address) {
                        toastr.warning('Address is required!');
                        $('#address').removeClass('is-valid');
                        $('#address').addClass('is-invalid');
                    } else {
                        $('#address').removeClass('is-invalid');
                        $('#address').addClass('is-valid');
                    }
                    return false;
                }
                return true;
            }
            $(document).ready(function() {
                $('#update').click(function() {
                    $(this).attr('disabled', true);
                    const fullname = $('#fullname').val();
                    const phone = $('#phone').val();
                    const mobile = $('#mobile').val();
                    const address = $('#address').val();
                    if (validate(fullname, phone, mobile, address)) {
                        toastr.info('Updating...');
                        $('#update-profile-form').submit()
                    } else
                        $(this).attr('disabled', false);
                })
            })
        </script>
    @endsection
