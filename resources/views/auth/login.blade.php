@include('components.plugin')
@include('components.topbar')
<div id="login">
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('img/undraw_file_sync_ot38.svg') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 logins">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <h3>Sign In to <strong>XSchool</strong></h3>
                            <p class="mb-4">Unlock a world of possibilities: Login to your account now.</p>
                        </div>
                        <form action="{{ route('login') }}" method="POST" id="login-form">
                            @csrf
                            <div class="form-group first field--not-empty" id="label-email">
                                <label for="email">Email</label>
                                <input type="email" class="form-control login-input" id="email" name="email"
                                    data-label="label-email" value="{{ old('email') }}">

                            </div>
                            <div class="form-group last mb-4 field--not-empty" id="label-password">
                                <label for="password">Password</label>
                                <input type="password" class="form-control login-input" id="password" name="password"
                                    data-label="label-password">

                            </div>

                            @if ($errors->any())
                                <div class="text-red-500 mt-3 mb-1" id="error">
                                    <h4>{{ $errors->first() }}</h4>
                                </div>
                            @endif

                            <div class="d-flex mb-5 align-items-center">
                                {{-- <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                    <input type="checkbox" checked="checked" />
                                    <div class="control__indicator"></div>
                                </label> --}}
                                <span class="ml-auto"><a href="/password/reset" class="forgot-pass">Forgot
                                        Password</a></span>
                            </div>

                            <button type="submit" class="btn text-white btn-block bg-sky-800 w-100 rounded"
                                id="submit">Log
                                in</button>

                            {{-- <span class="d-block text-left my-4 text-muted"> or sign in with</span>

                            <div class="social-login">
                                <a href="#" class="facebook">
                                    <span class="mr-3"><i class="fa-brands fa-facebook text-white"></i></span>
                                </a>
                                <a href="#" class="twitter">
                                    <span class="mr-3"><i class="fa-brands fa-twitter text-white"></i></span>
                                </a>
                                <a href="#" class="google">
                                    <span class="mr-3"><i class="fa-brands fa-google text-white"></i></span>
                                </a>
                            </div> --}}
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {


        $('#login-form').submit(function(e) {
            const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

            let email = $('#email').val();
            let password = $("#password").val();
            if (!email) {
                e.preventDefault();
                toastr.error("The email field is required.");
            } else if (!password) {
                e.preventDefault();
                toastr.error("The password field is required.");
            } else if (!email.match(validRegex)) {
                e.preventDefault();
                toastr.error("The email is invalid.");
            } else {
                $(this).submit();
            }

        });
    });
</script>
