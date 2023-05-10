@extends('components.layout')
@section('content')
    <div id="wrapper">
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold">
                        Create user
                    </div>

                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content relative">
                    <form class="container" method="POST" action="{{ route('admin.users.store') }}" id="create">
                        @csrf
                        <div class="form-group mt-3">
                            <label for="email" class="font-bold mb-1">Email address <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('gmail') is-invalid @enderror" id="email"
                                name="email" aria-describedby="emailHelp" placeholder="Enter email">
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="username" class="font-bold mb-1">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                id="username" name="username" placeholder="Enter your username">
                            @error('username')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label for="password" class="font-bold mb-1">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password">
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="repassword" class="font-bold mb-1">Confirm password <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control  @error('repassword') is-invalid @enderror"
                                id="repassword" name="repassword" placeholder="Confirm password">
                            @error('repassword')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="fullname" class="font-bold mb-1">Fullname <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                id="fullname" name="fullname" placeholder="Enter your name">
                            @error('fullname')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="role" class="font-bold mb-1">Role <span class="text-danger">*</span></label>
                            <select class="form-select form-control @error('role') is-invalid @enderror" id='role'
                                name='role' aria-label="Default select example">
                                <option selected value="0">Select role</option>
                                @foreach ($role as $key => $value)
                                    <option value="{{ $value }}">
                                        {{ $key }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="phone" class="font-bold mb-1">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Enter your phone">
                        </div>
                        <div class="form-group mt-3">
                            <label for="mobile" class="font-bold mb-1">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile"
                                placeholder="Enter your mobile">
                        </div>
                        <div class="form-group mt-3">
                            <label for="address" class="font-bold mb-1">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Enter your address">
                        </div>

                    </form>
                    <button type="submit" class=" btn bg-black text-white p-3 rounded-lg w-32 mb-5 float-right"
                        id="submit">submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // validate form
        const validate = (email, username, password, repassword, fullname, role) => {
            $('.form-control').removeClass('is-invalid');
            if (!email || !username || !repassword || password.length < 8 || password != repassword || !
                fullname || !role) {
                // Missing email
                if (!email) {
                    toastr.warning('Email field is requried.');
                    $('#email').addClass('is-invalid');
                } else {
                    $('#email').addClass('is-valid');
                };
                // Missing username
                if (!username) {
                    toastr.warning('Username field is requried.');
                    $('#username').addClass('is-invalid');
                } else {
                    $('#username').addClass('is-valid');
                };
                // Missing password
                if (!password) {
                    toastr.warning('Password field is requried.');
                    $('#password').addClass('is-invalid');
                } else {
                    $('#password').addClass('is-valid');
                };
                if (password.length < 8) {
                    toastr.warning('The password must be at least 8 characters.');
                    $('#password').addClass('is-invalid');
                } else {
                    $('#password').addClass('is-valid');
                };

                // Missing confirm password
                if (!repassword) {
                    toastr.warning('Confirm password field is requried.');
                    $('#repassword').addClass('is-invalid');
                };
                // Repassword incorrect
                if (password != repassword) {
                    toastr.warning('Confirm password is incorrect.');
                    $('#repassword').addClass('is-invalid');
                    $('#password').val("");
                    $('#repassword').val("");
                } else {
                    $('#repassword').addClass('is-valid');
                };
                // Missing fullname
                if (!fullname) {
                    toastr.warning('Fullname field is requried.');
                    $('#fullname').addClass('is-invalid');
                } else {
                    $('#fullname').addClass('is-valid');
                };
                // Missing role
                if (role == 0) {
                    toastr.warning('Role field is requried.');
                    $('#role').addClass('is-invalid');
                } else {
                    $('#role').addClass('is-valid');
                }
                return false;
            }
            return true;
        }
        // hanle submit form
        $(document).ready(function() {
            $('#submit').click(function() {
                const _token = '{{ csrf_token() }}';
                const email = $('#email').val();
                const username = $('#username').val();
                const password = $('#password').val();
                const repassword = $('#repassword').val();
                const fullname = $('#fullname').val();
                const role = $('#role').val();
                const isValid = validate(email, username, password, repassword, fullname, role);
                if (isValid) {
                    $('#create').submit();
                } else {
                    $(this).prop("disabled", "");
                }
            });
        });
    </script>
@endsection
