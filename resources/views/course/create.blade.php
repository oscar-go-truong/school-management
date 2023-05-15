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
                    aria-describedby="nameHelp" placeholder="Enter course name">
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="subjectSelect" class="font-bold mb-1">Subject <span class="text-danger">*</span></label>
                <select class="form-control select2" id="subjectSelect" name="subject_id">
                    <option value="" id="selectDefault1">-- Select subject --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" id="subject-{{ $subject->id }}">
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
                        <option value="{{ $teacher->id }}" id="teacher-{{ $teacher->id }}">
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
                    placeholder="Enter course descriptions" rows="8"></textarea>
                @error('descriptions')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </form>
        <div class="container">
            <label class="font-bold mb-1 mt-2">Schedules <span class="text-danger">*</span></label>
            <div id="schedule-group">
                <div class="schedule row" id="schedule-1">
                    <div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">Start<span class="text-danger">*</span></label>
                        <input class="form-control start" type='time' data-id="1" />
                    </div>
                    <div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">End<span class="text-danger">*</span></label>
                        <input class="form-control end" type='time' data-id="1" />
                    </div>
                    <div class="form-group mt-3 col-md-2">
                        <label for="" class=" mb-1">Weekday<span class="text-danger">*</span></label>
                        <select name="" id="" class="form-select form-control weekday" data-id="1"
                            id="select-weekday-1">
                            <option value="">--Select weekday--</option>
                            @foreach ($weekdays as $weekday)
                                <option value="{{ $weekday }}">{{ $weekday }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mt-3 col-md-2">
                        <label for="" class=" mb-1">Room<span class="text-danger">*</span></label>
                        <select name="" id="" class="form-select form-control weekday select2"
                            data-id="1" id="select-room-1">
                        </select>
                    </div>
                    <div class="form-group col-md-2">
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
            weekday: null
        }]
        const createSchedule = (id) => {
            let schedule = $(`<div class="schedule row" id="schedule-${id}">`);
            schedule.append(`<div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">Start<span class="text-danger">*</span></label>
                        <input class="form-control start" type='time' data-id="${id}"/>
                    </div>`);
            schedule.append(`<div class="form-group mt-3 col-md-3">
                        <label for="" class=" mb-1">End<span class="text-danger">*</span></label>
                        <input class="form-control end" type='time' data-id="${id}"/>
                    </div>`);
            let weekdayGroup = $(`<div class="form-group mt-3 col-md-2">`);
            weekdayGroup.append(` <label for="" class=" mb-1">Weekday<span class="text-danger">*</span></label>`);
            let weekdaySelect = $(`<select name="" id="" class="form-select form-control" data-id="${id}">`);
            weekdaySelect.append(`<option value="">--Select weekday--</option>`);
            for (let i = 0; i < weekdays.length; i++)
                weekdaySelect.append(`<option value="${weekdays[i]}">${weekdays[i]}</option>`);
            weekdayGroup.append(weekdaySelect);
            schedule.append(weekdayGroup);
            schedule.append(`<div class="form-group mt-3 col-md-2">
                        <label for="" class=" mb-1">Room<span class="text-danger">*</span></label>
                        <select name="" id="" class="form-select form-control weekday select2" data-id="1" id="select-room-${id}">
                        </select>
                    </div>`);
            schedule.append(
                ` <div class="form-group col-md-2 text-danger text-3xl text-center"><i class="fa-solid fa-delete-left pt-5 delete-schedule" data-id="${id}"></i></div>`
            );
            return schedule;
        }
        $('.select2').select2();
        // validate form
        const validate = (name, subject, teacher, start_time, finish_time, weekday, descriptions) => {
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
        // hanle submit form
        $(document).ready(function() {
            $('#submit').click(function() {
                const _token = '{{ csrf_token() }}';
                const name = $('#name').val();
                const descriptions = $('#descriptions').val();
                const subject = $('#subjectSelect').val();
                const teacher = $('#homeroomTeacherSelect').val();
                const isValid = validate(name, subject, teacher, descriptions);
                if (isValid) {
                    $('#create').submit();
                } else {
                    $(this).prop("disabled", "");
                }
            });
            $('#add-schedule-btn').click(function() {
                scheduleId++;
                schedules.push({
                    id: scheduleId,
                    start_time: null,
                    finish_time: null,
                    weekday: null
                })
                $('#schedule-group')
                const schedule = createSchedule(scheduleId);
                $('#schedule-group').append(schedule);
                $('.select2').select2();
            })
            $(document).on('click', '.delete-schedule', function() {
                const btn = $(this);
                const id = btn.data('id');
                const index = schedules.findIndex(item => item.id == id);
                schedules.splice(index, 1);
                $('#schedule-' + id).remove();
            })
        });
    </script>
@endsection
