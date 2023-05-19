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
                        </div>
                        <form action="/password/reset" method="POST" id="login-form">
                            @csrf
                            <input type="hidden" class="form-control login-input" name="token"
                                data-label="label-password" value="{{ $token }}">
                            <input type="hidden" class="form-control login-input" name="email"
                                data-label="label-password" value="{{ $email }}">
                            <div class="form-group last mb-4 field--not-empty" id="label-password">
                                <label for="password">Password</label>
                                <input type="password" class="form-control login-input" id="password" name="password"
                                    data-label="label-password">

                            </div>
                            <div class="form-group last mb-4 field--not-empty" id="label-password">
                                <label for="password-comfimation">Confirm Password</label>
                                <input type="password" class="form-control login-input" id="password_confirmation"
                                    name="password_confirmation" data-label="label-password-comfimation">

                            </div>
                            @if ($errors->any())
                                <div class="text-red-500 mt-3 mb-1" id="error">
                                    <h4>{{ $errors->first() }}</h4>
                                </div>
                            @endif



                            <button type="submit" class="btn text-white btn-block bg-sky-800 w-100 rounded"
                                id="submit">Submit</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
