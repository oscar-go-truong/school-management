@extends('components.layout')
@section('content')
    <div class="row">
        <div class="col-md-12 text-3xl font-bold">
            Create user
        </div>

    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    <div class="table-content relative">
        <form class="container" method="POST" action="/users" id="create">
            @csrf
            <div class="form-group mt-3">
                <label for="email" class="font-bold mb-1">Email address <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('gmail') is-invalid @enderror" id="email"
                    name="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="username" class="font-bold mb-1">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                    name="username" placeholder="Enter your username" value="{{ old('username') }}">
                @error('username')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="password" class="font-bold mb-1">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Password">
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="repassword" class="font-bold mb-1">Confirm password <span class="text-danger">*</span></label>
                <input type="password" class="form-control  @error('repassword') is-invalid @enderror" id="repassword"
                    name="repassword" placeholder="Confirm password">
                @error('repassword')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="fullname" class="font-bold mb-1">Fullname <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname"
                    name="fullname" placeholder="Enter your name" value="{{ old('fullname') }}">
                @error('fullname')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="role" class="font-bold mb-1">Role <span class="text-danger">*</span></label>
                <select class="form-select form-control @error('role') is-invalid @enderror" id='role' name='role'
                    aria-label="Default select example">
                    <option selected value="0">Select role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @if ($role->name === old('role')) selected @endif>
                            {{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="phone" class="font-bold mb-1">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone"
                    value="{{ old('phone') }}">
                @error('phone')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="mobile" class="font-bold mb-1">Mobile <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile"
                    value="{{ old('mobile') }}">
                @error('mobile')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="address" class="font-bold mb-1">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="address" name="address"
                    placeholder="Enter your address" value="{{ old('address') }}">
                @error('address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

        </form>
        <button type="submit" class=" btn bg-black text-white p-3 rounded-lg w-32 mb-5 float-right"
            id="submit">submit</button>
    </div>
    <script>
        // validate form
        const validate = (email, username, password, repassword, fullname, role, phone, mobile, address) => {
            const decimal = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,32}$/;
            $('.form-control').removeClass('is-invalid');
            $('.form-control').addClass('is-valid');
            if (!email || !username || !repassword || !password.match(decimal) || password != repassword || !
                fullname || !role || !phone || !mobile || !address) {
                // Missing email
                if (!email) {
                    toastr.warning('Email field is requried.');
                    $('#email').removeClass('is-valid');
                    $('#email').addClass('is-invalid');
                } else {
                    $('#email').removeClass('is-invalid');
                    $('#email').addClass('is-valid');
                };
                // Missing username
                if (!username) {
                    toastr.warning('Username field is requried.');
                    $('#username').removeClass('is-valid');
                    $('#username').addClass('is-invalid');
                } else {
                    $('#username').removeClass('is-invalid');
                    $('#username').addClass('is-valid');
                };
                if (password && !password.match(decimal)) {
                    toastr.warning(
                        'The password must have 8 to 32 characters which contain at least one lowercase letter, one uppercase letter, one numeric digit, and one special character.'
                    );
                    $('#password').removeClass('is-valid');
                    $('#password').addClass('is-invalid');
                } else {
                    $('#password').removeClass('is-invalid');
                    $('#password').addClass('is-valid');
                };


                // Repassword incorrect
                if (password && password != repassword) {
                    toastr.warning('Confirm password is incorrect.');
                    $('#repassword').removeClass('is-valid');
                    $('#repassword').addClass('is-invalid');
                    $('#password').val("");
                    $('#repassword').val("");
                } else {
                    $('#repassword').removeClass('is-invalid');
                    $('#repassword').addClass('is-valid');
                };

                // Missing confirm password
                if (!repassword) {
                    toastr.warning('Confirm password field is requried.');
                    $('#repassword').removeClass('is-valid');
                    $('#repassword').addClass('is-invalid');
                };

                // Missing password
                if (!password) {
                    toastr.warning('Password field is requried.');
                    $('#password').removeClass('is-valid');
                    $('#password').addClass('is-invalid');
                } else {
                    $('#password').removeClass('is-invalid');
                    $('#password').addClass('is-valid');
                };
                // Missing fullname
                if (!fullname) {
                    toastr.warning('Fullname field is requried.');
                    $('#fullname').removeClass('is-valid');
                    $('#fullname').addClass('is-invalid');
                } else {
                    $('#fullname').removeClass('is-invalid');
                    $('#fullname').addClass('is-valid');
                };
                // Missing role
                if (role == 0) {
                    toastr.warning('Role field is requried.');
                    $('#role').removeClass('is-valid');
                    $('#role').addClass('is-invalid');
                } else {
                    $('#role').removeClass('is-invalid');
                    $('#role').addClass('is-valid');
                }

                if (!phone) {
                    toastr.warning('Phone field is requried.');
                    $('#phone').removeClass('is-valid');
                    $('#phone').addClass('is-invalid');
                } else {
                    $('#phone').removeClass('is-invalid');
                    $('#phone').addClass('is-valid');
                }

                if (!mobile) {
                    toastr.warning('Mobile field is requried.');
                    $('#mobile').removeClass('is-valid');
                    $('#mobile').addClass('is-invalid');
                } else {
                    $('#mobile').removeClass('is-invalid');
                    $('#mobile').addClass('is-valid');
                }

                if (!address) {
                    toastr.warning('Address field is requried.');
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
                const phone = $('#phone').val();
                const mobile = $('#mobile').val();
                const address = $('#address').val();
                const isValid = validate(email, username, password, repassword, fullname, role, phone,
                    mobile, address);
                if (isValid) {
                    $('#create').submit();
                } else {
                    $(this).prop("disabled", "");
                }
            });
        });
    </script>
@endsection
