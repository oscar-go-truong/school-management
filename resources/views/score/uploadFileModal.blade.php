<style>

</style>
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFileModalLabel">Upload file</h5>
            </div>
            <div class="modal-body relative">
                <form id="upload-form">
                    <label for="data-sheet">Choose a data sheet:</label>
                    <input type="file" id="data-sheet" name="file" accept=".csv">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary bg-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary bg-primary" id="submit">upload</button>
            </div>
        </div>
    </div>
</div>
<script>
    const id = '{{ $exam->id }}'
    $(document).ready(function() {
        $('#submit').click(function() {
            let btn = $(this);
            const file = $('#data-sheet')[0].files[0];
            const formData = new FormData($('#upload-form')[0]);
            btn.attr('disabled', true);
            if (!file) {
                btn.attr('disabled', false);
                toastr.warning('Please upload file!');
            } else {
                $('#data-sheet').val('');
                toastr.info('Uploading!');
                $.ajax({
                    method: 'POST',
                    url: `/exams/${id}/scores/import-file`,
                    data: formData,
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function(resp) {
                        btn.attr('disabled', false);
                        $('#uploadFileModal').modal('hide');
                        if (resp.data) {
                            toastr.success(resp.message);
                            getTable(createRow);
                        } else toastr.error(resp.message);
                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        $('#uploadFileModal').modal('hide');
                        btn.attr('disabled', false);

                    }
                })
            }
        })
    })
</script>
