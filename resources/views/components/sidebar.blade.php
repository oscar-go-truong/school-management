<div class="d-flex flex-column flex-shrink-0 p-0 bg-white avbar-default navbar-side" role="navigation">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <img src="{{ asset('img/find_user.jpg') }}" class="img-responsive" />
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item {{ Request::is('/') ? 'bg-sky-300' : '' }}">
            <a href="/" class="nav-link link-dark" aria-current="page">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#profile"></use>
                </svg>
                Profile
            </a>
        </li>
        @if (Auth::User()->isAdministrator())
            <li class="nav-item {{ Request::is('users/*') || Request::is('users') ? 'bg-sky-300' : '' }}">
                <a href="/users" class="nav-link link-dark">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#user"></use>
                    </svg>
                    Users
                </a>
            </li>
        @endif
        <li class="nav-item {{ Request::is('subjects/*') || Request::is('subjects') ? 'bg-sky-300' : '' }}">
            <a href="/subjects" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#course"></use>
                </svg>
                Subjects
            </a>
        </li>
        <li class="nav-item {{ Request::is('courses/*') || Request::is('courses') ? 'bg-sky-300' : '' }}">
            <a href="/courses" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#course"></use>
                </svg>
                Courses
            </a>
        </li>
        <li class="nav-item {{ Request::is('requests/*') || Request::is('requests') ? 'bg-sky-300' : '' }}">
            <a href="/requests" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#Request"></use>
                </svg>
                Requests
            </a>
        </li>
        <li class="nav-item {{ Request::is('schedules/*') || Request::is('schedules') ? 'bg-sky-300' : '' }}">
            <a href="/schedules" class="nav-link link-dark">
                <svg class="bi me-2" width="16" height="16">
                    <use xlink:href="#schedule"></use>
                </svg>
                Schedules
            </a>
        </li>
    </ul>
</div>
