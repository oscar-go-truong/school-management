@include('components.plugin')

<body>
    <section>
        @include('components.topbar')
        @include('components.sidebar')
        @yield('content')
    </section>
</body>
