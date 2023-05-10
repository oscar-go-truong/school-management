<div class="modal fade" id="bookingRoomModal" tabindex="-1" aria-labelledby="bookingRoomModalLabel" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingRoomModalLabel">Booking room</h5>
            </div>
            <div class="modal-body relative">
                <div class="form-group">
                    <label for="bookingRoomFormControlSelect1">Select room:</label>
                    <select class="form-control select2" id="bookingRoomSelects">
                        <option value="" id="selectDefault">-- Select room --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" id="room-{{ $room->id }}">
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mt-3">
                    <label for="from">From:</label>
                    <input type="text" class="datepicker form-control" name="from" id="from">
                </div>
                <div class="form-group mt-3">
                    <label for="to">To:</label>
                    <input type="text" class="datepicker form-control" name="to" id="to">
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
        $('.datepicker').datepicker();
        $('#submit').click(function() {
            const roomId = $('#bookingRoomSelects').val();
            const from = $('#from').val();
            const to = $('#to').val();
            const CourseId = '{{ $course->id }}';
            let btn = $(this);
            btn.attr('disabled', true);
            if (!roomId) {
                toastr.warning('Please choose a course!');
                $('#bookingRoomSelects').addClass('is-invalid');
            } else {
                $('#bookingRoomSelects').removeClass('is-invalid');
                $('#bookingRoomSelects').addClass('is-valid');
            }
            if (!from) {
                toastr.warning('Please choose a start date!');
                $('#from').addClass('is-invalid');
            } else {
                $('#from').removeClass('is-invalid');
                $('#from').addClass('is-valid');
            }
            if (!to) {
                toastr.warning('Please choose a finish date!');
                $('#to').addClass('is-invalid');
            } else {
                $('#to').removeClass('is-invalid');
                $('#to').addClass('is-valid');
            }
            if (new Date(from) > new Date(to)) {
                toastr.warning('Start date must be less than finish date!');
                $('#from').addClass('is-invalid');
                $('#to').addClass('is-invalid');
            } else {
                $('#from').removeClass('is-invalid');
                $('#from').addClass('is-valid');
                $('#to').removeClass('is-invalid');
                $('#to').addClass('is-valid');
            }
            if (!roomId || !from || !to || new Date(from) > new Date(to)) {
                btn.attr('disabled', false);
            } else {
                $.ajax({
                    method: "POST",
                    url: '/requests/booking-room',
                    data: {
                        room_id: roomId,
                        course_id: CourseId,
                        booking_date_start: from,
                        booking_date_finish: to
                    },
                    dataType: 'json',
                    success: function(resp) {
                        if (resp.data) {
                            toastr.success(resp.message);
                        } else {
                            toastr.warning(resp.message);
                        }
                        $('#bookingRoomModal').modal('hide');
                        btn.attr('disabled', false);
                    },
                    error: function() {
                        toastr.error('Error, please try again later!');
                        $('#bookingRoomModal').modal('hide');
                        btn.attr('disabled', false);
                    }
                })
            }
        })
    })
</script>
