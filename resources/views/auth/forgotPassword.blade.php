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
                            <h3>Reset password <strong>XSchool</strong></h3>
                            <p class="mb-4">Please enter your email to reset password</p>
                        </div>
                        <form action="/password/email" method="POST" id="forgot-password-form">
                            @csrf
                            <div class="form-group first field--not-empty" id="label-email">
                                <label for="email">Email</label>
                                <input type="email" class="form-control login-input" id="email" name="email"
                                    data-label="label-email" value="{{ old('email') }}">
                            </div>
                            @if ($errors->any())
                                <div class="text-red-500 mt-3 mb-1" id="error">
                                    <h4>{{ $errors->first() }}</h4>
                                </div>
                            @endif
                            <button type="submit" class="btn text-white btn-block bg-sky-800 w-100 rounded mt-4"
                                id="submit">Submit</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#forgot-password').submit(function(e) {
            $('#submit').attr('disabled', true);
            const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            let email = $('#email').val();
            if (!email) {
                e.preventDefault();
                $('#submit').attr('disabled', false);
                toastr.error("The email field is required.");
            } else if (!email.match(validRegex)) {
                e.preventDefault();
                $('#submit').attr('disabled', false);
                toastr.error("The email is invalid.");
            } else {
                $(this).submit();
            }
        });
    });
</script>
