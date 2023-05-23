<nav id="sidebar">
    <div class="custom-menu" id="sidebarCollapse">
        <button type="button" class="btn btn-primary">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle Menu</span>
        </button>
    </div>
    <div class="p-4">
        <h1><a class="logo">XSchool <span class="text-sm">
                    Empowering Minds, Igniting Futures.</span></a></h1>
        <ul class="list-unstyled components mb-5">
            <li class="{{ Request::is('/') ? 'active' : '' }}">
                <a href="/" class="nav-link link-dark" aria-current="page">
                    <i class="fa-solid fa-user mr-3"></i>
                    Profile
                </a>
            </li>
            @role('admin')
                <li class="{{ Request::is('users/*') || Request::is('users') ? 'active' : '' }}">
                    <a href="/users" class="nav-link link-dark">
                        <i class="fa-solid fa-users mr-3"></i>
                        Users
                    </a>
                </li>
            @endrole
            <li class="{{ Request::is('subjects/*') || Request::is('subjects') ? 'active' : '' }}">
                <a href="/subjects" class="nav-link link-dark">
                    <i class="fa-solid fa-book mr-3"></i>
                    Subjects
                </a>
            </li>
            <li class="{{ Request::is('courses/*') || Request::is('courses') ? 'active' : '' }}">
                <a href="/courses" class="nav-link link-dark">
                    <i class="fa-solid fa-school mr-3"></i>
                    Courses
                </a>
            </li>
            <li class="{{ Request::is('exams/*') || Request::is('exams') ? 'active' : '' }}">
                <a href="/exams" class="nav-link link-dark">
                    <i class="fa-solid fa-file mr-3"></i>
                    Exams
                </a>
            </li>
            <li class="{{ Request::is('requests/*') || Request::is('requests') ? 'active' : '' }}">
                <a href="/requests" class="nav-link link-dark">
                    <i class="fa-sharp fa-solid fa-envelope mr-3"></i>
                    Requests
                </a>
            </li>
            <li class="{{ Request::is('schedules/*') || Request::is('schedules') ? 'active' : '' }}">
                <a href="/schedules" class="nav-link link-dark">
                    <i class="fa-solid fa-calendar mr-3"></i>
                    Schedules
                </a>
            </li>
        </ul>


        <div class="footer">
            <p>
                Copyright &copy;
                <script>
                    document.write(new Date().getFullYear());
                </script> All rights reserved | Made <i class="icon-heart" aria-hidden="true"></i>
                by <b>Oscar</b>
            </p>
        </div>

    </div>
</nav>
<script>
    $(document).ready(function() {
        $("#sidebarCollapse").click(function() {
            $("#sidebar").toggleClass("active");
        });
    })
</script>
