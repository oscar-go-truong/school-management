@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif
    <div class="container">
        <div class="response"></div>
        <div id='calendar'></div>
    </div>
    <script>
        $(document).ready(function() {
            const canAddEvent = `{{ Auth::user()->hasAnyRole('admin', 'teacher') }}`;
            const calendarEl = $('#calendar');
            var calendar = new FullCalendar.Calendar(calendarEl[0], {
                selectable: true,
                initialView: 'timeGridDay',
                customButtons: {
                    addEventButton: {
                        text: 'New event',
                        click: function() {
                            window.location.href = 'schedules/events/create';
                        }
                    }
                },
                eventMouseover: function(event, jsEvent, view) {
                    $(this).addClass('hovered');
                },
                eventMouseout: function(event, jsEvent, view) {
                    $(this).removeClass('hovered');
                },
                headerToolbar: {
                    left: canAddEvent ? 'addEventButton prev,next today' : 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,listWeek,timeGridWeek,dayGridMonth'
                },
            });
            toastr.info('Loading...');
            calendar.render();
            $.ajax({
                method: "GET",
                url: "/schedules/table",
                data: {},
                dataType: 'json',
                success: function(resp) {
                    if (resp.data) {
                        calendar.addEventSource(resp.data.events);
                        calendar.addEventSource(resp.data.schedules);
                    }
                },
                error: function() {
                    toastr.error('Error, please try again later!');
                }
            })
        });
    </script>
@endsection
