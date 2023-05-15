<div class="navbar navbar-inverse navbar-fixed-top d-flex justify-content-between">
    <div class="inline-block">
        <a class="navbar-brand" href="#"><i class="fa fa-square-o "></i>&nbsp;XShool</a>
        <button type="button" class="mr-5 ml-3">
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
        </button>
    </div>
    <div class="dropdown mr-5">
        @if (Auth::check())
            <a class="text-sm" href="/">
                <i class="fa-solid fa-address-card"></i> Hello
                {{ Auth::user()->fullname }}
            </a>
        @else
            <a class="text-sm" href="/login">
                Login now!
            </a>
        @endif
    </div>
</div>
