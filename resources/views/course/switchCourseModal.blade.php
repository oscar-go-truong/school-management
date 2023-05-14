<div class="modal fade" id="switchCourseModal" tabindex="-1" aria-labelledby="switchCourseModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="switchCourseModalLabel">Switch Course</h5>
            </div>
            <div class="modal-body relative">
                <div class="form-group">
                    <label for="switchCourseFormControlSelect1">Select courses:</label>
                    <select class="form-control select2" id="switchCourseSelects">
                        <option value="" id="selectDefault">-- Select course --</option>
                        @foreach ($coursesAvailableSwitch as $availableCourse)
                            <option value="{{ $availableCourse->id }}" id="course-{{ $availableCourse->id }}">
                                {{ $availableCourse->name }}
                            </option>
                        @endforeach

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary bg-primary" id="submit">Submit</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#submit').click(function() {
            const newCourseId = $('#switchCourseSelects').val();
            const oldCourseId = '{{ $course->id }}';
            let btn = $(this);
            btn.attr('disabled', true);
            if (!newCourseId) {
                toastr.warning('Please choose a course!');
                btn.attr('disabled', false);
            } else {
                $.ajax({
                    method: "POST",
                    url: '/requests/switch-course',
                    data: {
                        new_course_id: newCourseId,
                        old_course_id: oldCourseId
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.data) {
                            toastr.success(resp.message);
                            $('#modal-trigger').hide();
                            $('#switch-course-btn').empty().append(
                                '<div class="text-white bg-primary p-2">Requesting to change course...</div>'
                            )
                        } else {
                            toastr.error(resp.message);
                        }
                        $('#switchCourseModal').modal('hide');
                        btn.attr('disabled', false);
                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        $('#switchCourseModal').modal('hide');
                        btn.attr('disabled', false);
                    }
                })
            }
        })
    })
</script>
