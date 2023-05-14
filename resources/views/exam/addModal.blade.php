<div class="modal fade" id="addExamModal" tabindex="-1" aria-labelledby="addExamModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExamModalLabel">Add exam</h5>
            </div>
            <div class="modal-body relative">
                <div class="form-group">
                    <label for="addExamFormControlSelect1">Select Exams:</label>
                    <select class="form-control select2" id="addExamSelects">
                        <option value="" id="selectDefault">-- Select Type --</option>
                        @foreach ($examTypes as $key => $value)
                            <option value="{{ $value }}" id="exam-{{ $value }}">
                                {{ $key }}
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
            const examtypeId = $('#addExamSelects').val();
            const courseId = "{{ request()->query('courseId') }}";
            let btn = $(this);
            btn.attr('disabled', true);
            if (!examtypeId) {
                toastr.warning('Please select exam!');
                btn.attr('disabled', false);
            } else {
                toastr.info('Adding exam!');
                $.ajax({
                    type: "POST",
                    url: `/exams`,
                    data: {
                        type: examtypeId,
                        course_id: courseId
                    },
                    dataType: "json",
                    success: function(resp) {
                        if (resp.data) {
                            toastr.success(resp.message);
                            getTable(createRow);
                        } else
                            toastr.error('Error, please try again later!');
                        $('#addExamModal').modal('hide');
                        btn.attr('disabled', false);
                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        $('#addExamModal').modal('hide');
                        btn.attr('disabled', false);
                    }
                })
            }
        })
    });
</script>
