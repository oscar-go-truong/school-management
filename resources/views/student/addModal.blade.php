<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add student</h5>
            </div>
            <div class="modal-body relative">
                <div class="form-group">
                    <label for="addStudentFormControlSelect1">Select Students:</label>
                    <select class="form-control select2" id="addStudentSelects">
                        <option value="" id="selectDefault">-- Select Student --</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" id="student-{{ $student->id }}">
                                {{ $student->fullname }} - {{ $student->email }}
                            </option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary bg-primary" id="submit">Add</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#submit').click(function() {
            const studentId = $('#addStudentSelects').val();
            const courseId = '{{ $course->id }}';
            let btn = $(this);
            btn.attr('disabled', true);
            if (!studentId) {
                btn.attr('disabled', false);
                toastr.warning('Please select student!');
            } else {
                toastr.info('Adding student!');
                $.ajax({
                    type: "POST",
                    url: `/courses/${courseId}/students`,
                    data: {
                        user_id: studentId,
                        course_id: courseId
                    },
                    dataType: "json",
                    success: function(resp) {
                        if (resp.data) {
                            toastr.success(resp.message);
                            $('#student-' + studentId).remove();
                            getTable(createRow);
                        } else {
                            if (resp.wait) {
                                toastr.clear();
                                toastr.options.timeOut = 0;
                                toastr.options.extendedTimeOut = 0
                                toastr.options.closeButton = true;
                            }
                            toastr.warning(resp.message);
                        }
                        $('#addStudentModal').modal('hide');
                        btn.attr('disabled', false);
                        toastr.options.timeOut = 3000;
                        toastr.options.extendedTimeOut = 3000;

                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        btn.attr('disabled', false);
                        $('#addStudentModal').modal('hide');
                    }
                })
            }
        })
        $(document).on('click', '#changeCourse', function() {
            // Handler code goes here
            const id = $(this).data('id');
            const newCourseId = $(this).data('newcourseid');
            const oldCourseId = $(this).data('oldcourseid');
            const userId = $(this).data('userid');

            const data = {
                id: id,
                newCourseId: newCourseId,
                oldCourseId: oldCourseId,
                user_request_id: userId
            }

            toastr.info('Updating!');
            $.ajax({
                type: "PATCH",
                url: '/student/change-course',
                data: data,
                dataType: "json",
                success: function(resp) {
                    if (resp.data) {
                        toastr.success(resp.message);
                        $('#student-' + studentId).remove();
                        getTable(createRow);
                    } else
                        toastr.warning(resp.message);
                },
                error: function() {
                    toastr.error('Error, please try again later!');
                }
            });
        });
    });
</script>
