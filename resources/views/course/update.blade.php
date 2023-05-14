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
        <form class="container" method="POST" action='{{ route('admin.courses.update', $course->id) }}' id="update">
            @csrf
            {{ method_field('PATCH') }}
            <input type="hidden" value="{{ $course->id }}" />
            <div class="form-group mt-3">
                <label for="name" class="font-bold mb-1">Course name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    aria-describedby="nameHelp" placeholder="Enter course name" value='{{ $course->name }}'>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group mt-3">
                <label for="subjectSelect" class="font-bold mb-1">Subject <span class="text-danger">*</span></label>
                <select class="form-control select2" id="subjectSelect" name="subject_id">
                    <option value="" id="selectDefault1">-- Select subject --</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @if ($subject->id === $course->subject_id) selected @endif
                            id="subject-{{ $subject->id }}">
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
                            @if ($teacher->id === $course->owner_id) selected @endif>
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
                    placeholder="Enter course descriptions" rows="8">{{ $course->descriptions }}</textarea>
                @error('descriptions')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </form>
        <button type="submit" class=" btn bg-black text-white p-3 rounded-lg w-32 mb-5 float-right"
            id="submit">submit</button>
    </div>
    <script>
        $('.select2').select2();
        // validate form
        const validate = (name, subject, teacher, descriptions) => {
            teacher = 1;
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
                if (isValid)
                    $('#update').submit();
            });
        });
    </script>
@endsection
