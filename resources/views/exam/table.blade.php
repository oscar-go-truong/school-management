@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Subject</th>
        <th>Course</th>
        <th>Exam type</th>
        @role('student')
            <th>My score</th>
            <th>Marker</th>
        @endrole
        <th class="text-center">Scores</th>
        @role('student')
            <th class="text-center">Request review score</th>
        @endrole
    </tr>
@endsection
@section('tableId', 'course-examsTable')
<script>
    const model = 'course-exam';
    const tableId = '#course-examsTable';
    const url = '/exams/table';
    const isStudent = '{{ Auth::User()->hasRole('student') }}';
    const isAdmin = '{{ Auth::User()->hasRole('admin') }}';
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null,
        courseId: '{{ $courseId }}',
        year: new Date().getFullYear(),
    };;
    // end config
    //
    // Create row for table
    const createRow = (exam) => {
        let row = $(`<tr id="exam-${ exam.id }">`);
        row.append(`<td>${ exam.id }</td>`);
        row.append(`<td>${ exam.subject }</td>`);
        row.append(`<td>${ exam.course }</td>`);
        row.append(`<td>${ exam.type }</td>`);
        if (isStudent) {
            row.append(`<td>${exam.myScore}</td>`);
            row.append(`<td>${exam.marker}</td>`);
        }
        row.append(
            ` <td class="text-center text-secondary"><a href="/exams/${exam.id}/scores"><i class="fa-sharp fa-solid fa-circle-info"></i></a></td>`
        );
        if (isStudent)
            if (exam.myScore !== "") {
                if (!exam.requestStatus)
                    row.append(
                        `<td class="text-primary text-center" id="request-btn-${exam.id}"><i class="fa-solid fa-up-right-from-square create-request-review-score"  data-examId='${exam.id}' data-courseName='${exam.course}' data-subjectName='${exam.subject}' data-type='${exam.type}'></i></td>`
                    );
                else {
                    if (!exam.isUpdated)
                        row.append(
                            `<td class="text-primary text-center">${exam.requestStatus}</td>`
                        );
                    else
                        row.append(
                            `<td class="text-primary text-center">Updated</td>`
                        );
                }
            } else {
                row.append(
                    `<td class="text-primary text-center"></td>`
                );
            }
        return row;
    }

    const createRequestReviewScore = (examId) => {
        toastr.info('Creating request!');
        $.ajax({
            method: 'POST',
            url: '/requests/review-score',
            data: {
                exam_id: examId
            },
            dataType: 'json',
            success: function(resp) {
                if (resp.data) {
                    toastr.success(resp.message);
                    $('#request-btn-' + examId).html('Pending');
                } else {
                    toastr.error(resp.message);
                }
            },
            error: function() {
                toastr.error('Error, please try again later!');
            }

        })
    };

    $(document).on('click', '.create-request-review-score', function() {
        const btn = $(this);
        const examId = btn.data('examid');
        const courseName = btn.data('coursename');
        const subjectName = btn.data('subjectname');
        const type = btn.data('type');
        toastr.clear();
        toastr.options.timeOut = 0;
        toastr.options.extendedTimeOut = 0;
        toastr.options.closeButton = true;
        toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to create request reivew ${subjectName + " " + courseName + " " + type}  exam score!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-warning mr-3">No</button> 
                        <button class="btn btn-success ml-3" onclick='createRequestReviewScore(${examId})'>Yes</button></div>
                    </div>`);
        toastr.options.timeOut = 3000;
        toastr.options.extendedTimeOut = 3000;
    })
</script>
