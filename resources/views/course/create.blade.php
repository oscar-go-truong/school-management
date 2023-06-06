@extends('components.layout')
@section('content')
    @if (session('error'))
        <script>
            toastr.error('{{ session('error') }}')
        </script>
    @endif

    <div class="row">
        <div class="col-md-12 text-3xl font-bold">
            Create Course
        </div>

    </div>
    <!-- /. ROW  -->
    <hr class="mt-2 mb-3" />
    <!-- /. ROW  -->
    <div class="table-content relative">
        <form class="container" method="POST" action='{{ route('admin.courses.store') }}' id="create">
            @csrf
            <div class="form-group mt-3">
                <label for="name" class="font-bold mb-1">Course name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    aria-describedby="nameHelp" placeholder="Enter course name" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="subjectSelect" class="font-bold mb-1">Subject <span class="text-danger">*</span></label>
                <select class="form-control select2" id="subjectSelect" name="subject_id">
                    <option value="" id="selectDefault1">-- Select subject --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" id="subject-{{ $subject->id }}"
                            @if (old('subject_id') == $subject->id) checked @endif>
                            {{ $subject->name }}
                        </option>
                    @endforeach

                </select>
                @error('subject_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="homeroomTeacherSelect" class="font-bold mb-1">Homeroom teacher <span
                        class="text-danger">*</span></label>
                <select class="form-control select2" id="homeroomTeacherSelect" name="owner_id">
                    <option value="" id="selectDefault2">-- Select Teacher --</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" id="teacher-{{ $teacher->id }}"
                            @if (old('owner_id') == $teacher->id) checked @endif>
                            {{ $teacher->fullname }} - {{ $teacher->email }}
                        </option>
                    @endforeach

                </select>
                @error('owner_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mt-3">
                <label for="descriptions" class="font-bold mb-1">Descriptions <span class="text-danger">*</span></label>
                <textarea class="form-control @error('descriptions') is-invalid @enderror" id="descriptions" name="descriptions"
                    placeholder="Enter course descriptions" rows="8">{{ old('descriptions') }}</textarea>
                @error('descriptions')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <input type="hidden" value="" id="schedules-input" name="schedules" />
        </form>
        <div class="container">
            <label class="font-bold mb-1 mt-2">Schedules <span class="text-danger">*</span></label>
            @error('schedules')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
            <div id="schedule-group">
                <div class="schedule row" id="schedule-1">
                    <div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">Start<span class="text-danger">*</span></label>
                        <input class="form-control start" type='time' data-id="1" id="start-1" />
                    </div>
                    <div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">End<span class="text-danger">*</span></label>
                        <input class="form-control finish" type='time' data-id="1" id="finish-1" />
                    </div>
                    <div class="form-group mt-3 col-md-2">
                        <label for="select-weekday-1" class=" mb-1">Weekday<span class="text-danger">*</span></label>
                        <select class="form-select form-control weekday" data-id="1" id="select-weekday-1">
                            <option value="">--Select weekday--</option>
                            @foreach ($weekdays as $weekday)
                                <option value="{{ $weekday }}">{{ $weekday }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-3 col-md-2">
                        <label for="select-room-1" class=" mb-1">Room<span class="text-danger">*</span></label>
                        <select class="form-select form-control room select2" data-id="1" id="select-room-1">
                            <option value="">Available rooms</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2 text-danger text-3xl text-center"><i
                            class="fa-solid fa-delete-left pt-5 delete-schedule" data-id="1"></i>
                    </div>
                </div>
            </div>
            <button class="rounded btn text-3xl btn bg-success mt-3" id="add-schedule-btn" type="button">+</button>
        </div>
        <button type="submit" class=" btn bg-black text-white p-3 rounded-lg w-32 mb-5 float-right"
            id="submit">submit</button>
    </div>

    <script>
        let scheduleId = 1;
        const weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        let schedules = [{
            id: 1,
            start_time: null,
            finish_time: null,
            weekday: null,
            room: null
        }]
        const createSchedule = (id) => {
            let schedule = $(`<div class="schedule row" id="schedule-${id}">`);
            schedule.append(`<div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">Start<span class="text-danger">*</span></label>
                        <input class="form-control start" type='time' data-id="${id}" id="start-${id}"/>
                    </div>`);
            schedule.append(`<div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">End<span class="text-danger">*</span></label>
                        <input class="form-control finish" type='time' data-id="${id}" id="finish-${id}"/>
                    </div>`);
            let weekdayGroup = $(`<div class="form-group mt-3 col-md-2">`);
            weekdayGroup.append(` <label for="" class=" mb-1">Weekday<span class="text-danger">*</span></label>`);
            let weekdaySelect = $(
                `<select  class="form-select form-control weekday" data-id="${id}" id="select-weekday-${id}">`
            );
            weekdaySelect.append(`<option value="">--Select weekday--</option>`);
            for (let i = 0; i < weekdays.length; i++)
                weekdaySelect.append(`<option value="${weekdays[i]}">${weekdays[i]}</option>`);
            weekdayGroup.append(weekdaySelect);
            schedule.append(weekdayGroup);
            schedule.append(`<div class="form-group mt-3 col-md-2">
                        <label for="select-room-${id}" class=" mb-1">Room<span class="text-danger">*</span></label>
                        <select  class="form-select form-control room select2" id="select-room-${id}" data-id="${id}">
                            <option value="">Available rooms</option>
                        </select>
                    </div>`);
            schedule.append(
                ` <div class="form-group col-md-2 text-danger text-3xl text-center"><i class="fa-solid fa-delete-left pt-5 delete-schedule" data-id="${id}"></i></div>`
            );
            return schedule;
        }
        $('.select2').select2();
        const isConflict = (schedule1, schedule2) => {
            if (schedule1.weekday === schedule2.weekday) {
                const dateStart1 = new Date(`October 11, 2001 ${schedule1.start_time}:00`);
                const dateEnd1 = new Date(`October 11, 2001 ${schedule1.finish_time}:00`);

                const dateStart2 = new Date(`October 11, 2001 ${schedule2.start_time}:00`);
                const dateEnd2 = new Date(`October 11, 2001 ${schedule2.finish_time}:00`);

                const conflict = (dateStart1 >= dateStart2 && dateStart1 <= dateEnd2) || (dateEnd1 >= dateStart2 &&
                    dateEnd1 <= dateEnd2) || (dateStart1 <= dateStart2 && dateEnd1 >= dateEnd2);
                if (conflict) {
                    toastr.warning('Schedules is conflict time!');
                    $('#room-' + schedule1.id).addClass('is-invalid');
                    $('#start-' + schedule1.id).addClass('is-invalid');
                    $('#finish-' + schedule1.id).addClass('is-invalid');
                    $('#select-weekday-' + schedule1.id).addClass('is-invalid');

                    $('#room-' + schedule2.id).addClass('is-invalid');
                    $('#start-' + schedule2.id).addClass('is-invalid');
                    $('#finish-' + schedule2.id).addClass('is-invalid');
                    $('#select-weekday-' + schedule2.id).addClass('is-invalid');

                    return true;
                }
            }
            return false;
        }
        // validate form
        const validate = (name, subject, teacher, descriptions) => {
            $('.form-control').removeClass('is-invalid');

            if (!name || !subject || !teacher || !descriptions) {
                // Missing name
                if (!name) {
                    toastr.warning('Course name field is requried.');
                    $('#name').addClass('is-invalid');
                } else {
                    $('#name').addClass('is-valid');
                };
                // Missing subject
                if (!subject) {
                    toastr.warning('Subject field is requried.');
                    $('#subjectSelect').addClass('is-invalid');
                } else {
                    $('#subjectSelect').addClass('is-valid');
                };
                // Missing teacher
                if (!teacher) {
                    toastr.warning('Homeroom teacher field is requried.');
                    $('#homeroomTeacherSelect').addClass('is-invalid');
                } else {
                    $('#homeroomTeacherSelect').addClass('is-valid');
                };
                if (!descriptions) {
                    toastr.warning('Descriptions field is requried.');
                    $('#descriptions').addClass('is-invalid');
                } else {
                    $('#descriptions').addClass('is-valid');
                };
                return false;
            }
            return true;
        }
        const validateSchedules = () => {
            $('.room').removeClass('is-invalid');
            $('.start').removeClass('is-invalid');
            $('.finish').removeClass('is-invalid');
            $('.weekday').removeClass('is-invalid');
            for (let schedule of schedules) {
                for (let key in schedule) {
                    if (!schedule[key]) {
                        toastr.warning('Schedule is invalid!');
                        const id = schedule.id;
                        $('#room-' + id).addClass('is-invalid');
                        $('#start-' + id).addClass('is-invalid');
                        $('#finish-' + id).addClass('is-invalid');
                        $('#select-weekday-' + id).addClass('is-invalid');
                        return false;
                    }
                }
            }
            for (let i = 0; i < schedules.length; i++) {
                for (let j = i + 1; j < schedules.length; j++) {
                    return !isConflict(schedules[i], schedules[j]);
                }
            }
            $('.room').addClass('is-valid');
            $('.start').addClass('is-valid');
            $('.finish').addClass('is-valid');
            $('.weekday').addClass('is-valid');
            return true;
        }
        // hanle submit form
        $(document).ready(function() {
            $('#submit').click(function() {
                $(this).prop("disabled", true);
                const _token = '{{ csrf_token() }}';
                const name = $('#name').val();
                const descriptions = $('#descriptions').val();
                const subject = $('#subjectSelect').val();
                const teacher = $('#homeroomTeacherSelect').val();
                const isValid = validate(name, subject, teacher, descriptions) && validateSchedules();
                if (isValid) {
                    $('#schedules-input').val(JSON.stringify(schedules));
                    $('#create').submit();
                } else {
                    $(this).prop("disabled", false);
                }
            });
            $('#add-schedule-btn').click(function() {
                scheduleId++;
                schedules.push({
                    id: scheduleId,
                    start_time: null,
                    finish_time: null,
                    weekday: null,
                    room: null
                })
                $('#schedule-group')
                const schedule = createSchedule(scheduleId);
                $('#schedule-group').append(schedule);
                $('.select2').select2();
                if (schedules.length <= 1)
                    $('.delete-schedule').attr('hidden', true);
                else
                    $('.delete-schedule').attr('hidden', false);
            })
            $(document).on('change', '.start, .finish, .weekday', function() {
                const input = $(this);
                const id = input.data('id');
                const start_time = $('#start-' + id).val();
                const finish_time = $('#finish-' + id).val();
                const weekday = $('#select-weekday-' + id).val();

                const dateStart = new Date(`October 11, 2001 ${start_time}:00`);
                const dateEnd = new Date(`October 11, 2001 ${finish_time}:00`);
                if (start_time && finish_time && weekday) {
                    if (dateStart.getTime() >= dateEnd.getTime()) {
                        toastr.warning('Range time is in valid!');
                        $('#finish-' + id).removeClass('is-valid');
                        $('#finish-' + id).addClass('is-invalid');
                        $('#start-' + id).removeClass('is-valid');
                        $('#start-' + id).addClass('is-invalid');
                    } else {
                        $('#start-' + id).removeClass('is-invalid');
                        $('#start-' + id).addClass('is-valid');
                        $('#finish-' + id).removeClass('is-invalid');
                        $('#finish-' + id).addClass('is-valid');
                        $('#select-weekday-' + id).removeClass('is-invalid');
                        $('#select-weekday-' + id).addClass('is-valid');
                        toastr.info('Loading available room!');
                        const scheduleIndex = schedules.findIndex(obj => obj.id === id);
                        schedules[scheduleIndex].room = null;
                        $.ajax({
                            method: "GET",
                            url: "/rooms/available-room-for-schedule",
                            data: {
                                start_time: start_time,
                                finish_time: finish_time,
                                weekday: weekday
                            },
                            success: function(resp) {
                                $('#select-room-' + id).html(
                                    '<option value="">Available rooms</option>');
                                const rooms = resp.data.rooms;
                                for (let i = 0; i < rooms.length; i++) {
                                    $('#select-room-' + id).append(
                                        `<option value="${rooms[i].id}">${rooms[i].name}</option>`
                                    );
                                }
                            },
                            error: function() {
                                toastr.error('Error, please try again later!');
                            }
                        });
                    }
                }

            })
            $(document).on('click', '.delete-schedule', function() {
                const btn = $(this);
                const id = btn.data('id');
                const index = schedules.findIndex(item => item.id == id);
                schedules.splice(index, 1);
                $('#schedule-' + id).remove();
                if (schedules.length <= 1)
                    $('.delete-schedule').attr('hidden', true);
                else
                    $('.delete-schedule').attr('hidden', false);
            })
            $(document).on('change', '.start', function() {
                const input = $(this);
                const id = input.data('id');
                const value = input.val();
                const scheduleIndex = schedules.findIndex(obj => obj.id === id);
                schedules[scheduleIndex].start_time = value;
            });
            $(document).on('change', '.finish', function() {
                const input = $(this);
                const id = input.data('id');
                const value = input.val();
                const scheduleIndex = schedules.findIndex(obj => obj.id == id);
                schedules[scheduleIndex].finish_time = value;
            });
            $(document).on('change', '.weekday', function() {
                const input = $(this);
                const id = input.data('id');
                const value = input.val();
                const scheduleIndex = schedules.findIndex(obj => obj.id === id);
                schedules[scheduleIndex].weekday = value;
            });
            $(document).on('change', '.room', function() {
                const input = $(this);
                const id = input.data('id');
                const value = input.val();
                const scheduleIndex = schedules.findIndex(obj => obj.id === id);
                schedules[scheduleIndex].room = value;
            });
        });
    </script>
@endsection
