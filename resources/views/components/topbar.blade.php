<div class="navbar navbar-inverse navbar-fixed-top d-flex justify-content-between">
    <div class="inline-block">
        <a class="navbar-brand" href="#"><i class="fa fa-square-o "></i>&nbsp;XShool</a>
        {{-- <button type="button" class="mr-5 ml-3">
            Home
        </button>
        <button type="button" class="mr-5">
            Services
        </button>
        <button type="button" class="mr-5">
            About us
        </button>
        <button type="button" class="mr-5">
            Contact
        </button> --}}
    </div>
    <div class="dropdown mr-5 inline">
        @if (Auth::check())
            <a class="text-sm" href="/">
                <i class="fa-solid fa-address-card"></i> Hello
                {{ Auth::user()->fullname }}
            </a>
            <div class="dropdown inline ml-2 mr-2">
                <a id="dLabel" role="button" data-bs-toggle="dropdown"
                    class="dropdown-toggle @if (Auth::user()->notifications()->unread_count) red-dot @else no-after @endif"
                    aria-expanded="false" data-content="{{ Auth::user()->notifications()->unread_count }}">
                    <i class="fa-regular fa-bell text-xl"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-end le="menu" aria-labelledby="dLabel">

                    <div class="container">
                        <h4 class="menu-title">Notifications</h4>
                        </h4>
                    </div>
                    <li class="border-b-2 my-1"></li>
                    <div class="w-80 p-1 bg-gray-500 notification-wrapper" id="notifications">
                        @foreach (Auth::user()->notifications()->notifications as $notification)
                            <div
                                class="container py-1 border-1 @if ($notification->read_at) bg-white @else bg-gray-300 @endif">
                                <a class="content" href="/notifications/{{ $notification->id }}">
                                    <div class="text-sm">{{ $notification->title }}</div>
                                    <div class="item-info text-xs ">{{ $notification->message }}</div>

                                </a>
                            </div>
                        @endforeach
                    </div>
                    <li class="divider"></li>
                    <div class="notification-footer" id="view-all-notification-btn">
                        <div class="mt-1 font-bold text-sm">View all <i class="fa-solid fa-circle-arrow-down"></i></div>
                    </div>
                </ul>

            </div>
        @else
            <a class="text-sm" href="/login">
                Login now!
            </a>
        @endif
    </div>
</div>
