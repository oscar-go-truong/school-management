@include('components.plugin')
<div id="loading" hidden>
    <div class="spinner"></div>
</div>

<body>
    @include('components.topbar')
    <section class="relative">
        <div class="wrapper d-flex align-items-stretch">
            @include('components.sidebar')
            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5">
                @yield('content')
            </div>
        </div>


    </section>
</body>
