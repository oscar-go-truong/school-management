@include('components.plugin')


<body>
    <div id="loading" hidden>
        <div class="spinner"></div>
    </div>
    @include('components.topbar')
    <section class="relative">
        <div class="wrapper d-flex align-items-stretch">

            @include('components.sidebar')
            <!-- Page Content  -->
            <div id="content" class="p-4 p-md-5 pt-5 relative">
                @yield('content')
            </div>
        </div>


    </section>

</body>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
    const addNewNotification = (notification) => {
        toastr.info(`<b>${notification.title}</b><div>${notification.message}</div>`);
        $('#notifications').prepend(`<div class="container py-1 bg-gray-300 border-1 "> <a class="content" href="/notifications/${notification.id}">
        <div class="text-sm">${notification.title}</div>
        <div class="item-info text-xs text-dark">${notification.message}</div>
        </a></div>`);

        $('#dLabel').removeClass('no-after');
        $('#dLabel').addClass('red-dot');

        let unread_count = $('#dLabel').attr('data-content');
        console.log(unread_count);
        $('#dLabel').attr('data-content', Number(unread_count) + 1);
    }
    $(document).ajaxStart(function() {
        $("#loading").attr('hidden', false);
    });
    $(document).ajaxStop(function() {
        $("#loading").attr('hidden', true);
    });
    let pusher = new Pusher('437e9230a81396996c39', {
        cluster: 'ap1'
    });


    $('#loading').attr('hidden', true);
    let channel = pusher.subscribe('users.' + '{{ Auth::user()->id }}');
    channel.bind('courses', function(data) {
        addNewNotification(data);
    });
    channel.bind('events', function(data) {
        addNewNotification(data);
    });
    channel.bind('requests', function(data) {
        addNewNotification(data);
    });
    if ("{{ Auth::user()->hasRole('admin') }}") {
        let channelAdmin = pusher.subscribe('admin');
        channelAdmin.bind('requests', function(data) {
            addNewNotification(data);
        });
    }
    $(document).ready(function() {
        $('#view-all-notification-btn').click(function() {
            toastr.info('Loading...');
            $.ajax({
                method: 'GET',
                url: '/notifications',
                success: function(resp) {
                    $('#notifications').html('');
                    for (let i = 0; i < resp.length; i++)
                        $('#notifications').append(`<div class="container py-1 ${resp[i].read_at?"bg-white":"bg-gray-300"} border-1 "> 
                                                        <a class="content" href="/notifications/${resp[i].id}">
                                                            <div class="text-sm">${resp[i].title}</div>
                                                            <div class="item-info text-xs text-dark">${resp[i].message}</div>
                                                        </div>
                                                    </a>
                                                    </div>`);
                },
                error: function() {
                    toastr.error('Error, please try again later!');
                }
            })
        });
    });
</script>
