<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">



            <li>
                <a href="/"><i class="fa fa-desktop "></i>Profile</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-edit "></i>Requests</a>
            </li>
            @if (Auth::User()->isAdministrator())
                <li>
                    <a href="#"><i class="fa fa-table "></i>User Management</a>
                </li>
            @endif


            <li>
                <a href="#"><i class="fa fa-sitemap "></i>Subject</a>

            </li>
            <!-- <li>
                <a href="#"><i class="fa fa-qrcode "></i>Tabs & Panels</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-bar-chart-o"></i>Mettis Charts</a>
            </li> -->

            <li>
                <a href="#"><i class="fa fa-edit "></i>Schedule</a>
            </li>

        </ul>

    </div>

</nav>
