@extends('components.layout')
@section('content')
    <div id="wrapper">
        @if (session('error'))
            <script>
                toastr.error('{{ session('error') }}')
            </script>
        @endif
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 text-3xl font-bold d-flex justify-content-between">
                        <div> Courses managerment</div>


                    </div>
                </div>
                <!-- /. ROW  -->
                <hr class="mt-2 mb-3" />
                <!-- /. ROW  -->
                <div class="table-content">
                    <table class="table table-striped table-bordered table-hover" id='courseTable'>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Descriptions</th>
                                <th>Homeroom Teacher</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr id="course-{{ $course->id }}">
                                    <td>{{ $course->id }}</td>
                                    <td>{{ $course->name }}</td>
                                    <td>{{ $course->descriptions }}</td>
                                    <td>{{ $course->homeroomTeacher->fullname }}</td>
                                    <td>{{ $course->subject->name }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status" type="checkbox" id="{{ $course->id }}"
                                                data-id="{{ $course->id }}" {{ $course->status === 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $course->id }}">
                                                {{ $course->status === 1 ? 'active' : 'inactive' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-primary"><a href="/courses/{{ $course->id }}/edit"><i
                                                class="fa-solid fa-pen-to-square"></i></a></td>
                                    <td class="text-danger"><i class="fa-sharp fa-regular fa-calendar-xmark delete"
                                            data-id={{ $course->id }} data-name={{ $course->name }}></i></i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>



            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <script>
        const deleteCourse = (id) => {
            toastr.info('Deleting course!');
            $.ajax({
                type: "DELETE",
                url: "/courses/" + id,
                dataType: "json",
                success: function() {
                    toastr.success('Delete course successful!');
                    $('#course-' + id).remove();
                },
                error: function() {
                    toastr.error('Error, Please try again later!');
                }
            });;
        }
        $(document).ready(function() {
            let _token = '{{ csrf_token() }}';
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': _token
                }
            });
            let table = $('#courseTable').dataTable({
                scrollY: 600
            });
            // delete Course
            $(document).on('click', '.delete', function() {
                const name = $(this).data('name');
                const id = $(this).data('id');

                toastr.options.timeOut = 0;
                toastr.options.extendedTimeOut = 0;
                toastr.options.closeButton = true;
                toastr.options.preventDuplicates = true;
                toastr.warning(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to delete course <b>${name}!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-secondary mr-3">cancel</button> 
                        <button class="btn btn-danger ml-3" onclick='deleteCourse(${id})'>delete</button></div>
                    </div>`);
                toastr.options.timeOut = 600;
                toastr.options.extendedTimeOut = 600;
            });
            //change status course 
            $('.status').change(function() {
                toastr.info(`updating course's status!`);
                const val = $(this).is(':checked') ? 1 : 0;
                const id = $(this).data('id');
                $.ajax({
                    type: "PATCH",
                    url: "/courses/status/" + id,
                    data: {
                        id: id,
                        status: val
                    },
                    dataType: "json",
                    success: function() {
                        toastr.success(`update course's status successful!`);
                    },
                    error: function() {
                        toastr.error('Error, Please try again later!');
                    }
                });;
            })
        })
    </script>
@endsection
