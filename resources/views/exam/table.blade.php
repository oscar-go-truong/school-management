@extends('components.tableLayout')
@section('th')
    <tr>
        <th>#</th>
        <th>Subject</th>
        <th>Course</th>
        <th>Exam type</th>
        @if (Auth::User()->isStudent())
            <th>My score</th>
        @endif
        <th class="text-center">Scores</th>
        <th>Created at</th>
        @if (Auth::user()->isAdministrator())
            <th>Status</th>
        @endif
        @if (Auth::User()->isStudent())
            <th class="text-center">Request review score</th>
        @endif
    </tr>
@endsection
@section('tableId', 'course-examsTable')
<script>
    const model = 'course-exam';
    const tableId = '#course-examsTable';
    const url = '/' + '{{ Request::path('/') }}' + '/table';
    const isStudent = '{{ Auth::User()->isStudent() }}';
    const isAdmin = '{{ Auth::User()->isAdministrator() }}';
    let queryData = {
        page: 1,
        orderBy: 'id',
        orderDirect: 'asc',
        search: null,
        role: [],
        status: null,
    };
    let last_page = 1;
    // end config
    //
    // Create row for table
    const createRow = (exam) => {
        let row = $(`<tr id="exam-${ exam.id }">`);
        row.append(`<td>${ exam.id }</td>`);
        row.append(`<td>${ exam.course.subject.name }</td>`);
        row.append(`<td>${ exam.course.name }</td>`);
        row.append(`<td>${ exam.type }</td>`);
        if (isStudent)
            row.append(`<td>${exam.score[0] ? exam.score[0].total : ""}</td>`);
        row.append(
            ` <td class="text-center text-secondary"><a href="/exams/${exam.id}/scores">${exam.score_count}</a></td>`
        );
        row.append(
            `<td>${  new Date(exam.created_at).toLocaleDateString('en-us', { weekday:"long", year:"numeric", month:"short", day:"numeric"})  }</td>`
        );
        if (isAdmin)
            row.append(`<td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="${ exam.id }"
                    data-id="${ exam.id }" ${ exam.status === 1 ? 'checked' : '' }>
                    <label class="form-check-label" for="${ exam.id }">
                    ${ exam.status === 1 ? 'active' : 'blocked' }
                    </label>
                    </div>
                    </td>`);
        if (isStudent)
            row.append(
                `<td class="${exam.wasRequestedByUser !== 0 ? "":exam.wasApprovedRequestedByAdmin !==0 ? "text-success":"text-primary"} text-center request-btn">${exam.wasRequestedByUser !== 0 ? 'Request pending' :exam.wasApprovedRequestedByAdmin?"Aprroved": `<i class="fa-solid fa-up-right-from-square create-request-review-score"  data-examId='${exam.id}' data-courseName='${exam.course.name}' data-subjectName='${exam.course.subject.name}' data-type='${exam.type}'></i>` }</td>`
            );
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
                    $('#exam-' + examId + '.request-btn').html('Requested');
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
        toastr.options.closeButton = true;
        toastr.info(`<div class="z-10">
                    <div class="mb-10">Are you sure is you want to create request reivew ${subjectName + " " + courseName + " " + type}  exam score!</b></div>
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-warning mr-3">No</button> 
                        <button class="btn btn-success ml-3" onclick='createRequestReviewScore(${examId})'>Yes</button></div>
                    </div>`);
        toastr.options.timeOut = 600;
        toastr.options.extendedTimeOut = 600;
    })
</script>
