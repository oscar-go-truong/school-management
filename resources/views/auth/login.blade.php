<x-layout>

    @include('partials.topbar')
    <div class="container" style="margin-top:100px">
        <div class="row">
            <div class="col-md-3 offset-md-3">
            </div>
            <div class="col-md-6 offset-md-3">
                <div class="text-center text-6xl">Login</div>

                <div class="card my-5">

                    <form class="card-body bg-slate-300 p-lg-5 p-8 rounded-lg pb-32" method="POST" action="/login">
                        @csrf
                        <div class="text-center">
                            <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png"
                                class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px"
                                alt="profile">
                        </div>

                        <div class="mb-3">
                            <input type="email" class="form-control" id="email" name="email"
                                aria-describedby="emailHelp" placeholder="email">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="password">
                        </div>
                        <div class="text-red-500 ml-1 mt-5">
                            @if ($errors->any())
                                <h4>{{ $errors->first() }}</h4>
                            @endif
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit"
                                class=" bg-black text-white w-full p-3 rounded-sm mb-5">Login</button>
                        </div>
                        <div id="emailHelp" class="form-text text-center mb-5 text-dark">Not
                            Registered? <a href="#" class="text-dark fw-bold"> Create an
                                Account</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-layout>
