@include('components.plugin')


<body>
    @include('components.topbar')
    <section class="relative">
        <div class="wrapper d-flex align-items-stretch">

            @include('components.sidebar')
            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5 relative">
                <div id="loading" hidden>
                    <div class="spinner"></div>
                </div>
                @yield('content')
            </div>
        </div>


    </section>
</body>
<script>
    $(document).ajaxStart(function() {
        $("#loading").attr('hidden', false);
    });

    $(document).ajaxStop(function() {
        $("#loading").attr('hidden', true);
    });
</script>
