<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add teacher</h5>
            </div>
            <div class="modal-body relative">
                <div class="form-group">
                    <label for="addTeacherFormControlSelect1">Select Teachers:</label>
                    <select class="form-control select2" id="addTeacherSelects">
                        <option value="" id="selectDefault">-- Select Teacher --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" id="teacher-{{ $teacher->id }}">
                                {{ $teacher->fullname }} - {{ $teacher->email }}
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
            const teacherId = $('#addTeacherSelects').val();
            const courseId = '{{ $course->id }}';
            let btn = $(this);
            btn.attr('disabled', true);
            if (!teacherId) {
                btn.attr('disabled', false);
                toastr.warning('Please select teacher!');
            } else {
                toastr.info('Adding teacher!');
                $.ajax({
                    type: "POST",
                    url: `/courses/${courseId}/teachers`,
                    data: {
                        user_id: teacherId,
                        course_id: courseId
                    },
                    dataType: "json",
                    success: function(resp) {
                        if (resp.data) {
                            toastr.success(resp.message);
                            $('#teacher-' + teacherId).remove();
                            getTable(createRow);
                        } else {
                            toastr.warning(resp.message);
                        }
                        $('#addTeacherModal').modal('hide');
                        btn.attr('disabled', false);

                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        $('#addTeacherModal').modal('hide');
                        btn.attr('disabled', false);
                    }
                })
            }
        })
    });
</script>
