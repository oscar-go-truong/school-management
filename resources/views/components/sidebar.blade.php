<div class="d-flex flex-column flex-shrink-0 p-3 bg-white avbar-default navbar-side" role="navigation">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <img src="{{ asset('img/find_user.jpg') }}" class="img-responsive" />
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/" class="nav-link link-dark" aria-current="page">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#profile"></use>
                </svg>
                Profile
            </a>
        </li>
        @if (Auth::User()->isAdministrator())
            <li>
                <a href="/users" class="nav-link link-dark">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#user"></use>
                    </svg>
                    User management
                </a>
            </li>
        @endif
        <li>
            <a href="/request" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#Request"></use>
                </svg>
                Request
            </a>
        </li>
        <li>
            <a href="/course" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#course"></use>
                </svg>
                Course
            </a>
        </li>
        <li>
            <a href="/schedule" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#schedule"></use>
                </svg>
                Schedule
            </a>
        </li>
    </ul>
</div>
