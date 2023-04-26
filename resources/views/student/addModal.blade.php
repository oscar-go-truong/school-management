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
            if (!studentId)
                toastr.warning('Please select student!');
            else {
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
                        } else
                            toastr.error('Error, please try again later!');
                        $('#addStudentModal').modal('hide');
                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        $('#addStudentModal').modal('hide');
                    }
                })
            }
        })
    });
</script>
