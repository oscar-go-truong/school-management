@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif

    <div class="row">
        <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
            <div> New event</div>
        </div>
    </div>
    <!-- /. ROW  -->
    <hr class="mt-3 mb-3" />
    <!-- /. ROW  -->
    <div class="container blank-content" id="form-create-event">

        <form class="well form-horizontal" action="/schedules/events" method="post" id="event-form">
            @csrf
            <fieldset class="row">

                <legend>Make your event now!</legend>

                <div>
                    <div class="form-group">
                        <label class="control-label mt-3 font-semibold">Event Tag</label>
                        <div class="inputGroupContainer">
                            <div class="input-group">
                                <input name="name" placeholder="Event Name" class="form-control" type="text"
                                    id='name'>
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="font-bold text-xl mt-3">Time & Room</label>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <div class="inputGroupContainer relative">
                                <label for="date" class="mt-3  font-semibold">Start</label>
                                <input type="date" class="datepicker form-control" id="date" name="date">
                                @error('date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="inputGroupContainer relative">
                                <label for="startTime" class="mt-3  font-semibold">Start</label>
                                <input type="time" class="datepicker form-control" id="startTime" step="3600"
                                    min="9:00" max="18:00" name="start_time">
                                @error('start_time')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <div class="inputGroupContainer relative">
                                <label for="endTime" class="mt-3  font-semibold">End</label>
                                <input type="time" class="datepicker form-control" id="endTime" step="3600"
                                    min="9:00" max="18:00" name='end_time'>
                                @error('end_time')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label mt-3  font-semibold">Available Room</label>
                            <div class="inputRoomContainer">
                                <select class="form-control select2" id="roomSelect" name="room_id">

                                </select>
                                @error('room_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <label class="font-bold text-xl mt-3">Participants</label>
                    <div class="form-group col-md-6">
                        <label class="control-label mt-3  font-semibold">Groups</label>
                        <div class="inputGroupContainer">
                            <select class="form-control select2" name="courses[]" id="groupSelect" multiple>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" id="course-{{ $course->id }}">
                                        {{ $course->subject->name }} {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('courses')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="control-label mt-3  font-semibold">Users</label>
                        <div class="inputGroupContainer">
                            <select class="form-control select2" name="users[]" id="userSelect" multiple>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" id="user-{{ $user->id }}">
                                        {{ $user->fullname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('users')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label mt-3  font-semibold">Description</label>
                    <div class="inputGroupContainer">
                        <div class="input-group">
                            <textarea placeholder="Description..." rows="10" class="form-control" id="description" name="description"></textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


            </fieldset>
        </form>
        <div class="float-right">
            <button class="btn btn-secondary">Cancel</button>
            <button class="btn btn-primary" id="submit">Submit</button>
        </div>
    </div>
    </div><!-- /.container -->
    <script>
        const getAvailableRooms = (date, startTime, endTime) => {
            toastr.info('Loading available room...');
            $.ajax({
                method: 'GET',
                url: '/rooms/available',
                data: {
                    date: date,
                    start_time: startTime,
                    end_time: endTime
                },
                dataType: 'json',
                success: function(resp) {
                    if (resp.data) {
                        const rooms = resp.data.rooms;
                        if (rooms.length) {
                            $('#roomSelect').append(
                                '<option value="" id="defaultRoomSelect">-- Select Available Room --</option>'
                            );
                            toastr.success('Loading done!');
                            for (let i = 0; i < resp.data.rooms.length; i++)
                                $('#roomSelect').append(
                                    ` <option value="${rooms[i].id}" id="room-${rooms[i].id}">${rooms[i].name}</option>`
                                )
                        } else {
                            $('#roomSelect').html('');
                            toastr.warning('No available rooms found!');
                        }
                    }
                },
                error: function() {

                }
            })
        }
        const validateform = (name, startTime, endTime, date, room, users, description) => {
            const dateStart = new Date(`October 11, 2001 ${startTime}:00`);
            const dateEnd = new Date(`October 11, 2001 ${endTime}:00`);

            if (!name || !startTime || !endTime || !date || !room || !users || !description || dateStart.getTime() >=
                dateEnd.getTime()) {
                if (!name) {
                    toastr.warning('Event tag field is require!');
                    $('#name').removeClass('is-valid');
                    $('#name').addClass('is-invalid');
                } else {
                    $('#name').removeClass('is-invalid');
                    $('#name').addClass('is-valid');

                }

                if (!startTime) {
                    toastr.warning('Start time field is require!');
                    $('#startTime').removeClass('is-valid');
                    $('#startTime').addClass('is-invalid');
                } else {
                    $('#startTime').removeClass('is-invalid');
                    $('#startTime').addClass('is-valid');

                }

                if (!endTime) {
                    toastr.warning('End time field is require!');
                    $('#endTime').removeClass('is-valid');
                    $('#endTime').addClass('is-invalid');
                } else {
                    $('#endTime').removeClass('is-invalid');
                    $('#endTime').addClass('is-valid');

                }

                if (dateStart.getTime() >= dateEnd.getTime()) {
                    toastr.warning('Range time is in valid!');
                    $('#endTime').removeClass('is-valid');
                    $('#endTime').addClass('is-invalid');
                    $('#startTime').removeClass('is-valid');
                    $('#startTime').addClass('is-invalid');
                } else {
                    $('#startTime').removeClass('is-invalid');
                    $('#startTime').addClass('is-valid');
                    $('#endTime').removeClass('is-invalid');
                    $('#endTime').addClass('is-valid');
                }

                if (!date) {
                    toastr.warning('Date field is require!');
                    $('#date').removeClass('is-valid');
                    $('#date').addClass('is-invalid');
                } else {
                    $('#date').removeClass('is-invalid');
                    $('#date').addClass('is-valid');

                }

                if (!room) {
                    toastr.warning('Room field is require!');
                    $('#room').removeClass('is-valid');
                    $('#room').addClass('is-invalid');
                } else {
                    $('#room').removeClass('is-invalid');
                    $('#room').addClass('is-valid');

                }

                if (!users) {
                    toastr.warning('Participant field is require!');
                    $('#users').removeClass('is-valid');
                    $('#users').addClass('is-invalid');
                } else {
                    $('#users').removeClass('is-invalid');
                    $('#users').addClass('is-valid');

                }

                if (!description) {
                    toastr.warning('Description field is require!');
                    $('#description').removeClass('is-valid');
                    $('#description').addClass('is-invalid');
                } else {
                    $('#description').removeClass('is-invalid');
                    $('#description').addClass('is-valid');

                }
                return false;
            }
            return true;
        }
        $(document).ready(function() {
            $(".select2").select2();
        });
        $('#date, #startTime, #endTime').change(function() {
            const start = $('#startTime').val();
            const end = $('#endTime').val();
            const date = $('#date').val();
            if (start && end && date)
                getAvailableRooms(date, start, end);
        })
        $('#submit').click(function() {
            const startTime = $('#startTime').val();
            const endTime = $('#endTime').val();
            const date = $('#date').val();
            const name = $('#name').val();
            const groups = $('#groupSelect').val();
            const users = $('#userSelect').val();
            const room = $('#roomSelect').val();
            const description = $('#description').val();
            if (validateform(name, startTime, endTime, date, room, users, description))
                $('#event-form').submit();
        })
    </script>
@endsection
